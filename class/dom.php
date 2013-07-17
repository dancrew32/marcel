<?
class dom {
	# http://simplehtmldom.sourceforge.net
	# http://simplehtmldom.sourceforge.net/manual.htm
	static function get_html($url) {
		require_once VENDOR_DIR.'/dom/dom.php';	
		return file_get_html($url);
	}

	static function set_html($html) {
		require_once VENDOR_DIR.'/dom/dom.php';	
		return str_get_html($html);
	}
}
