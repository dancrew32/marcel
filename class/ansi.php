<?
class ansi {
	static function to_html($ansi_text) {
		$pre = config::$setting['vendor_dir'] .'/ansi-to-html/SensioLabs/AnsiConverter';
		$deps = [
			'AnsiToHtmlConverter.php',
			'Theme/Theme.php',
			'Theme/SolarizedTheme.php',
		];
		foreach ($deps as $d)
			require_once "{$pre}/{$d}";
		$theme = new SensioLabs\AnsiConverter\Theme\SolarizedTheme;
		$converter = new SensioLabs\AnsiConverter\AnsiToHtmlConverter($theme);
		$html = $converter->convert($ansi_text);
		return str_replace('[m', '', $html);
	}
}
