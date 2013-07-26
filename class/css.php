<?
class css {
	static function get_html() {
		$out = '';	
		foreach (array_unique(app::$assets['css']) as $c)
			$out .= '<link href="'. $c .'" rel="stylesheet" type="text/css">';
		return $out;
	}

	static function parse($css_text) {
		// TODO: hook up parser
	}

	static function to_attribute($property, $value) {
		# https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes
		$value = trim($value);
		$property = trim($property);
		switch ($property) {
			case 'color':
				return [
					'attr'  => 'color',
					'value' => $value,
				];
				break;
			case 'height':
				return [
					'attr'  => 'height',
					'value' => (int) $value,
				];
				break;
			case 'width':
				return [
					'attr'  => 'width',
					'value' => (int) $value,
				];
				break;
			case 'background':
			case 'background-color':
				return [
					'attr'  => 'bgcolor',
					'value' => $value,
				];
			break;
		}
		return false;
	}
}
