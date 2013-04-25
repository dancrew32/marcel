<?
class note {
	private static function cookie_name($name) {
		return 'cnote_'.$name;	
	}

	public static function get($name, $session=false) {
		$cookie_name = self::cookie_name($name);	
		$value = false;
		if ($session) {
			if (!isset($_SESSION[$cookie_name])) return false;
			$value = $_SESSION[$cookie_name];
			unset($_SESSION[$cookie_name]);
		} else {
			# Cookie
			$value = stripslashes(cookie::get($cookie_name));
			cookie::delete($cookie_name);
		}
		return $value;
	}

	public static function set($name, $value, $session=false) {
		$cookie_name = self::cookie_name($name);	
		if ($session)
			$_SESSION[$cookie_name] = $value;
		else
			cookie::set($cookie_name, $value, time::ONE_MINUTE*2);
	}
}
