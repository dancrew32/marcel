<?
/*
 * Helpers for database stuff
 */
class db {
	static function dtnow($timestamp=false) {
		if ($timestamp)
			return date('Y-m-d H:i:s', $timestamp);
		return date('Y-m-d H:i:s');	
	}
	static function dnow() {
		return date('Y-m-d');	
	}
}
