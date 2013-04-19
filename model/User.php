<?
class User extends model {
	static $table_name = 'users';
	static $roles = [
		'admin'   => 'Admin',
		'manager' => 'Manager',
		'user'    => 'User',
	];

	static $validates_inclusion_of = [
		['role', 'in' => [
			'admin', 
			'manager', 
			'user'
		], 'message' => 'Invalid user role.'],
	];

	static $logged_in = false;
	static $user = [];

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
			$r->last_login = db::dtnow();
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

}
