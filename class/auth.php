<?
class auth {

/*
 * USERS
 */
	static function admin($user=null) {
		return take(self::for_user($user), 'role') == 'admin';
	}	

	static function manager($user=null) {
		$role = take(self::for_user($user), 'role');
		return in_array($role, ['manager', 'admin']);
	}

	static function user($user=null) {
		$role = take(self::for_user($user), 'role');
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

	static function product_section() {
		return self::admin();
	}

	static function product_type_section() {
		return self::admin();
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

	static function for_user($user=false) {
		if ($user) return $user;
		return User::$user;
	}

}
