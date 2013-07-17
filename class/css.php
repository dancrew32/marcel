<?
class css {
	static function get_html() {
		$out = '';	
		foreach (array_unique(app::$assets['css']) as $c)
			$out .= '<link href="'. $c .'" rel="stylesheet" type="text/css">';
		return $out;
	}
}
