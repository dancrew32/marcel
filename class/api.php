<?
class api {
	static $keys = [];

	static function get_key($api) {
		require_once CONFIG_DIR.'/api.php';
		$vendor = take(self::$keys, $api);
		return take($vendor, ENV);
	}
}
