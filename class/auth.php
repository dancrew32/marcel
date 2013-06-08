<?
class auth {

	static function can(array $features, $user=null) {
		$user = $user ? $user : User::$user;
		$user_type_id = $user ? take($user, 'user_type_id', false) : 4;
		$set = take(User_Permission::$instance, $user_type_id);
		if (!$set) return false;
		return count(array_intersect($set, $features));
	}

	static function only(array $features, $user=null) {
		if (!CLI && !self::can($features)) _403();
	}

	static function check($allowed, $fail='_403') {
		if (!$allowed) $fail();
	}

	static function for_user($user=false) {
		if ($user) return $user;
		if (!isset(User::$user->user_type_id)) return false;
		return User::$user->user_type;
	}

}
