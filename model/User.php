<?
class User extends model {
	static $table_name = 'users';

	static $logged_in = false;
	static $user = [];
	static $roles = [
		'admin'   => 'Admin',
		'manager' => 'Manager',
		'user'    => 'User',
	];

/*
 * VALIDATION
 */
	static $validates_inclusion_of = [
		['role', 'in' => [
			'admin', 
			'manager', 
			'user'
		], 'message' => 'Invalid user role.'],
	];

	static $validates_presence_of = [
		['email'],
		['password'],
		['role'],
	];

	static $validates_uniqueness_of = [
		['email',    'message' => 'is taken.'],
		['username', 'message' => 'is taken.'],
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
			case 'password':
				$this->assign_attribute($name, trim($value));
				break;
			default: 
				$this->assign_attribute($name, $value);
		}
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


/*
 * STATIC
 */
	static function init() {
		self::$logged_in = take($_SESSION, 'in', false);
		if (!self::$logged_in) return false;

		$id = (int) take($_SESSION, 'id');
		$cache_key  = cache::keygen(__CLASS__, __FUNCTION__, $id);
		self::$user = cache::get($cache_key, $found, true);
		if (!$found) {
			self::$user = User::find($id);
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
			return self::find_by_id($key);
		if (strpos($key, '@'))
			return self::find_by_email($key);
		return self::find_by_username($key);
	}

	static function logout() {
		session_destroy();
		app::redir('/');
	}

	static function spass($p) {
		return md5(SALT.$p.SALT);	
	}

	static function badge_class($status) {
		return $status ? 'label-success' : '';
	}

}
