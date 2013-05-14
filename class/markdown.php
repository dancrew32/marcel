<?
class markdown {
	static function render($file) {
		$deps = [
			'Markdown',
			'MarkdownExtra',
		];
		foreach ($deps as $dep)
			require_once VENDOR_DIR."/markdown/Michelf/{$dep}.php";

		$markdown = file_get_contents($file);
		return \Michelf\MarkdownExtra::defaultTransform($markdown);
	}
}
