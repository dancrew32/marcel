<?
class auth {

/*
 * USERS
 */
	static function is_admin() {
		return take(User::$user, 'role') == 'admin';
	}	

	static function is_manager() {
		$role = take(User::$user, 'role');
		return in_array($role, ['manager', 'admin']);
	}

	static function is_user() {
		$role = take(User::$user, 'role');
		return in_array($role, ['user', 'manager', 'admin']);
	}


}
