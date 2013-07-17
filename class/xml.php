<?
class xml {
	static function parse($xml) {
		return simplexml_load_string($xml);
	}
}
