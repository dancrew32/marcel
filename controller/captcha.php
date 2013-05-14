<?
class controller_captcha extends controller_base {

	function __construct($o) {
		$this->root_path = app::get_path('Captcha Home');
		parent::__construct($o);
   	}

	function get() {
		$captcha = captcha::get();
		header('Content-type: image/png');
		imagepng($captcha);
	}

	function post() {
		$code = take($_POST, 'code');
		$ok = captcha::test($code);
		pd($ok);
	}

}
