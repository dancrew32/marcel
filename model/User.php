<?
class User extends model {
	static $table_name = 'users';

	const BCRYPT_COST = 10; # 4 min, 31 max
	static $logged_in = false;
	static $user = [];
	static $roles = [
		'admin'   => 'Admin',
		'manager' => 'Manager',
		'user'    => 'User',
	];

	static $safe_columns = [
		'id',
		'user_type_id',
		'email',
		'username',
		'active',
		'first',
		'last',
		'created_at',
		'updated_at',
		'last_login',
		'last_login_ip',
		'login_count',
	];


/*
 * RELATIONSHIPS
 */
	static $has_one = [
		[ 'cart' ],
	];

	static $belongs_to = [
		[ 'user_type', 'class_name' => 'User_Type' ]	
	];


/*
 * EVENTS
 */
	static $after_update  = ['update_cache'];
	static $after_destroy = ['delete_cache'];


/*
 * VALIDATION
 */
	static $validates_presence_of = [
		['email'],
		['password'],
		['user_type_id'],
	];

	static $validates_uniqueness_of = [
		['email',    'message' => 'is taken'],
		['username', 'message' => 'is taken'],
	];

	static $validates_format_of = [
		['email', 'with' => '/@/']
	];

	
/*
 * INSTANCE
 */
	function __set($name, $value) {
		switch ($name) {
			case 'first':
			case 'last':
			case 'email':
			case 'username':
				$this->assign_attribute($name, trim($value));
				break;
			case 'password':
				$this->assign_attribute($name, self::spass(trim($value)));
				$this->assign_attribute('salt', self::make_salt(trim($value)));
				break;
			default: 
				$this->assign_attribute($name, $value);
		}
	}

	function &__get($name) {
		switch ($name) {
			case 'first':
			case 'last':
			case 'email':
			case 'username':
				$out = h($this->read_attribute($name));
			default:
				return $this->read_attribute($name);
		}
		return $out;
	}

	function full_name() {
		$name = take($this, 'first').' '.take($this, 'last');	
		return trim($name);
	}

	function badge($cls='') {
		$cls = isset($cls{0}) ? " {$cls}" : '';
		$html = "<span class=\"label{$cls}";
		$badge_class = self::badge_class($this->active);
		if ($this->active) {
			$html .= " {$badge_class}\">Active</span>";		
		} else {
			# TODO: banned
			$html .= '">Inactive</span>';		
		}
		return $html;
	}


	function delete_cache() {
		return cache::delete(self::cache_key($this->id));
	}

	function update_cache() {
		self::$user = $this;
		return cache::set(self::cache_key($this->id), self::$user, time::ONE_HOUR, true);
	}


/*
 * STATIC
 */
	static function cache_key($id) {
		return cache::keygen(__CLASS__, __FUNCTION__, $id);
	}

	static function init() {
		self::$logged_in = take($_SESSION, 'in', false);
		if (!self::$logged_in) return false;

		$id = (int) take($_SESSION, 'id');
		$cache_key  = self::cache_key($id);
		self::$user = cache::get($cache_key, $found, true);
		if (!$found) {
			self::$user = User::find('first', [
				'select' => implode(', ', self::$safe_columns),
				'conditions' => [ 'id = ?', $id ],
			]);
			cache::set($cache_key, self::$user, time::ONE_HOUR, true);
		}
	}

	static function login($u, $p) {
		$p = self::spass($p);
		$r = self::id_or_email_or_username($u);
		$match = take($r, 'password') == $p;
		if ($match && $r->active) {
			$_SESSION['in'] = 1;
			$_SESSION['id'] = take($r, 'id');
			self::$logged_in = true;
			$r->last_login = time::now();
			$r->last_login_ip = take($_SERVER, 'REMOTE_ADDR', null);
			$r->login_count++;
			$r->save();
			self::$user = $r;
			cache::delete(self::cache_key(self::$user->id));
		} else {
			unset($_SESSION['in']);
			unset($_SESSION['id']);
			self::$logged_in = false;
			self::$user = [];
		}
		return $match;
	}

	static function id_or_email_or_username($key) {
		if (is_numeric($key))
			return self::find('first', [ 'conditions' => [ 'id = ?', $key ] ]);
		if (strpos($key, '@'))
			return self::find('first', [ 'conditions' => [ "email = ?", $key ] ]);
		return self::find('first', [ 'conditions' => [ "username = ?", $key ] ]);
	}

	static function logout() {
		cache::delete(self::cache_key(self::$user->id));
		session_destroy();
		return true;
	}

	static function spass($password) {
		require_once VENDOR_DIR.'/password_compat/lib/password.php';
		return password_hash($password, PASSWORD_BCRYPT, [
			'cost' => self::BCRYPT_COST,
			'salt' => self::make_salt($password),
		]);
	}

	static function make_salt($password) {
		return password_hash($password, PASSWORD_BCRYPT, [
			'cost' => self::BCRYPT_COST, 
			'salt' => md5(SALT.$password.SALT)
		]);
	}

	static function rehash($password, $hash) {
		require_once VENDOR_DIR.'/password_compat/lib/password.php';
		if (!password_verify($password, $hash)) return false;
		if (!password_needs_rehash($hash, PASSWORD_BCRYPT, [
			'cost' => self::BCRYPT_COST,
			'salt' => self::make_salt($password),
		])) return false;
		return self::spass($password); // store this in db now
	}

	static function badge_class($status) {
		return $status ? 'label-success' : '';
	}

}
