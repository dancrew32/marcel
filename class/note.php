<?
class note {
	private static function cookie_name($name) {
		return 'cnote_'.$name;	
	}

	public static function get($name) {
		$cookie_name = self::cookie_name($name);	

		if (!$cookie_name) return false;

		$value = stripslashes(cookie::get($cookie_name));
		cookie::delete($cookie_name);
		return $value;
	}

	public static function set($name, $value) {
		$cookie_name = self::cookie_name($name);	
		cookie::set($cookie_name, $value, time::ONE_MINUTE*2);
	}
}
