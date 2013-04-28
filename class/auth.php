<?
class auth {

/*
 * USERS
 */
	static function admin() {
		return take(User::$user, 'role') == 'admin';
	}	

	static function manager() {
		$role = take(User::$user, 'role');
		return in_array($role, ['manager', 'admin']);
	}

	static function user() {
		$role = take(User::$user, 'role');
		return in_array($role, ['user', 'manager', 'admin']);
	}

	static function anon() {
		return !User::$logged_in;
	}	


/*
 * FEATURES
 */
	static function cron_job_section() {
		return self::admin();
	}

	static function join() {
		return self::anon();
	}

	static function login() {
		return self::anon();
	}

	static function user_section() {
		return self::admin();
	}

	static function worker_section() {
		return self::admin();
	}


/*
 * HELPERS
 */
	static function check($method) {
		if (!method_exists(__CLASS__, $method)) 
			return true;
		$allowed = self::$method();
		if (!$allowed) _403();
	}

}
