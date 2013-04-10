<?
class html {
	static function a($href, $text) {
		return '<a href="'. $href .'">'. $text .'</a>';
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
