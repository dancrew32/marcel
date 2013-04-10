<?
class html {
	static function a($href, $text, $cls=false) {
		$cls = $cls ? " class=\"{$cls}\"" : '';
		return "<a href=\"{$href}\"{$cls}>{$text}</a>";
	}

	static function btn($href, $text) {
		return self::a($href, $text, 'btn');	
	}

	static function ul(array $data) {
		return '<ul>'. _list($data, 'li') .'</ul>';
	}

	private static function _list(array $data, string $type) {
		$html = '';
		$count = 0;
		$len = count($data) - 1;
		foreach	($data as $d) {
			if (!$count)
				$cls = ' class="alpha"';
			if ($count == $len)
				$cls = ' class="omega"';
			$html .= "<$type}{$cls}>{$d}</{$type}>";
			$count++;
		}
		return $html;
	}
}
