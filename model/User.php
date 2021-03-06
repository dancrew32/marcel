<?
class User extends model {
	static $table_name = 'users';

	const BCRYPT_COST = 10; # 4 min, 31 max
	static $logged_in = false;
	static $user = [];

	static $safe_columns = [
		'id',
		'user_type_id',
		'email',
		'username',
		'active',
		'verified',
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

	static $has_many = [
		[ 'user_verification', 'class_name' => 'User_Verification' ],
	];

	static $belongs_to = [
		[ 'user_type', 'class_name' => 'User_Type' ],
	];



/*
 * EVENTS
 */
	static $after_update  = ['update_cache'];
	static $after_destroy = ['after_destroy'];


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

	function email_domain() {
		return util::explode_pop('@', $this->email);
	}

	function badge($cls='') {
		$cls = isset($cls{0}) ? " {$cls}" : '';
		$html = "<span class=\"label{$cls}";
		$badge_class = self::badge_class($this->active);
		if ($this->active) {
			if ($this->verified)
				$html .= " {$badge_class}\">Active</span>";		
			else
				$html .= " label-warning\">Not Verified</span>";		
		} else {
			# TODO: banned
			$html .= '">Inactive</span>';		
		}
		return $html;
	}

	# worker
	function join_worker() {
		return Worker::add([
			'class'  => __CLASS__,
			'method' => 'join_process',
			'args'   => [
				'user_id' => $this->id,
			],
		]);
	}

	function send_verification_email() {
		$uv = User_Verification::create([ 
			'user_id' => $this->id,
		   	'hash'    => User_Verification::generate_hash($this),
		]);

		$m = new mail;
		$m->from = 'dan@l.danmasq.com';
		$m->from_name = 'Dan Masquelier';
		$m->add_address($this->email);
		$m->subject = 'Thanks for joining! Please verify your address.';
		$m->body = r('user', 'email_join', [
			'verification_url' => $uv->url(),
		]);
		$m->queue();
		return true;
	}

	function after_destroy() {
		# delete cache
		cache::delete(self::cache_key($this->id));

		# log user out (must queue to avoid table lock)
		Session::queue_delete_user_by_id($this->id);

		# clean up unused verifications
		User_Verification::queue_delete_by_user_id($this->id);

		return true;
	}

	function update_cache() {
		self::refresh_session($this->id);
		return cache::set(self::cache_key($this->id), self::$user, time::ONE_HOUR, true);
	}


/*
 * STATIC
 */
	static function cache_key($id) {
		return cache::keygen(__CLASS__, __FUNCTION__, $id);
	}

	static function refresh_session($id) {
		self::$user = User::find('first', [
			'select' => implode(', ', self::$safe_columns),
			'conditions' => [ 'id = ?', $id ],
		]);
	}

	static function init() {
		self::$logged_in = take($_SESSION, 'in', false);
		if (!self::$logged_in) return false;

		$id = (int) take($_SESSION, 'id');
		$cache_key  = self::cache_key($id);
		self::$user = cache::get($cache_key, $found, true);
		if (!$found) {
			self::refresh_session($id);
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
		require_once config::$setting['vendor_dir'].'/password_compat/lib/password.php';
		return password_hash($password, PASSWORD_BCRYPT, [
			'cost' => self::BCRYPT_COST,
			'salt' => self::make_salt($password),
		]);
	}

	static function make_salt($password) {
		$salt = config::$setting['salt'];
		return password_hash($password, PASSWORD_BCRYPT, [
			'cost' => self::BCRYPT_COST, 
			'salt' => md5($salt.$password.$salt)
		]);
	}

	static function rehash($password, $hash) {
		require_once config::$setting['vendor_dir'].'/password_compat/lib/password.php';
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

	static function join_process(array $args) {
		$user_id = take($args, 'user_id');
		$user = self::find_by_id($user_id);

		return $user->send_verification_email();
	}


}
