<?
class controller_ocr extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('OCR Home');
		auth::only(['ocr']);
		parent::__construct($o);
   	}

	function get() {
		$config = config::$setting;
		//$img = file_get_contents("{$config['image_dir']}/ocr/foo.png");
		//$img = file_get_contents("{$config['image_dir']}/ocr/test.jpg");
		//$img = file_get_contents('http://www.google.com/recaptcha/static/images/recaptcha-example.gif');
		//$img = file_get_contents('https://www.google.com/recaptcha/api/image?c=03AHJ_VutYzGVKMOVUT1PL3OVWnLETuVG_e_ghv5TaZ-5svD_pa_fNvboMdxaQOf1_TJUGebg6Fpps6uBE0wmu50f998YpbPTGFc3V250terylAL2Quf7KbCrODCR2DhDHE50DCLqxxCgAcbIrVm4N9IGAclijx8uIG9_9UeSqzuCPUF9q2enLixw');
		$img = file_get_contents(route::get_absolute('Captcha Home'));
		$tmp = "{$config['tmp_dir']}/ocr/temp.png";
		file_put_contents($tmp, $img);
		echo ocr::get($tmp, ['method' => ocr::SINGLE_WORD]);
		unlink($tmp);
	}
}
