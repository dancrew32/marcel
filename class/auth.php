<?
class auth {

/*
 * USERS
 */
	static function admin($user=null) {
		return take(self::for_user($user), 'slug') == 'admin';
	}	

	static function manager($user=null) {
		$role = take(self::for_user($user), 'slug');
		return in_array($role, ['manager', 'admin']);
	}

	static function user($user=null) {
		$role = take(self::for_user($user), 'slug');
		return in_array($role, ['user', 'manager', 'admin']);
	}

	static function anon() {
		return !User::$logged_in;
	}	


/*
 * FEATURES
 */
	static function cron_job_section($user=null) {
		return self::admin($user);
	}

	static function join($user=null) {
		return self::anon($user);
	}

	static function login($user=null) {
		return self::anon($user);
	}

	static function product_section($user=null) {
		return self::admin($user);
	}

	static function product_type_section($user=null) {
		return self::admin($user);
	}

	static function product_category_section($user=null) {
		return self::admin($user);
	}

	static function user_section($user=null) {
		return self::admin($user);
	}

	static function user_type_section($user=null) {
		return self::admin($user);
	}

	static function worker_section($user=null) {
		return self::admin($user);
	}


/*
 * HELPERS
 */
	static function check($method, $fail='_403') {
		if (!method_exists(__CLASS__, $method)) 
			return true;
		$allowed = self::$method();
		if (!$allowed) $fail();
	}

	static function for_user($user=false) {
		if ($user) return $user;
		if (!isset(User::$user->user_type_id)) return false;
		return User::$user->user_type;
	}

}
