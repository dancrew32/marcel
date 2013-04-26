<?
class api {
	static $keys = [];

	static function get_key($api) {
		$vendor = take(self::$keys, $api);
		return $vendor[ENV];
	}
}
