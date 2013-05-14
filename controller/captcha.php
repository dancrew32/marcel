<?
class controller_captcha extends controller_base {
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
