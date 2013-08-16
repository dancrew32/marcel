<?
class markdown {
	static function init() {
		$deps = [
			'Markdown',
			'MarkdownExtra',
		];
		foreach ($deps as $dep)
			require_once config::$setting['vendor_dir']."/markdown/Michelf/{$dep}.php";
	}

	static function render($file) {
		self::init();
		$markdown = file_get_contents($file);
		return \Michelf\MarkdownExtra::defaultTransform($markdown);
	}

	static function render_text($text) {
		self::init();
		return \Michelf\MarkdownExtra::defaultTransform($text);
	}
}
