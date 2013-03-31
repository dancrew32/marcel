<?
class dom {
	# http://simplehtmldom.sourceforge.net
	static function get_html($url) {
		require_once VENDOR_DIR.'/dom/dom.php';	
		return file_get_html($url);
	}
}
