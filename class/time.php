<?
class time {
	const ONE_YEAR   = 31557600;
	const ONE_DAY    = 86400;
	const ONE_HOUR   = 3600;
	const ONE_MINUTE = 60;

	static function now() {
		return date('Y-m-d H:i:s');	
	}
}
