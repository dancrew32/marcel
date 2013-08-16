<?
class api {
	static $keys = [];

	static function get_key($api) {
		require_once config::$setting['config_dir'].'/api.php';
		$vendor = take(self::$keys, $api);
		return take($vendor, ENV);
	}
}
