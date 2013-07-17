<?
class code {
	static function highlight() {
		app::asset('class/rainbow', 'js');
		app::asset('core/github', 'css');
	}

	static function vim($lang=null) {
		app::asset('core/code', 'css');
		app::asset('core/code.dialog', 'css');
		app::asset('core/twilight', 'css');
		app::asset('class/code.init', 'js');
	}
}
