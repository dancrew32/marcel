<?
class color {

	# $c = new color('f00f00');
	# $c->lighten(5); # ff1a0b

	private $instance;

	static function init() {
		require_once config::$setting['vendor_dir'] .'/color/src/color.php';
	}

	function __construct($color) {
		self::init();
		$this->instance = new phpColors\Color($color); ;
		return $this;
	}

	function __toString() {
		return $this->instance;
	}

	function darken($percent=5) {
		return $this->instance->darken($percent);
	}

	function lighten($percent=5) {
		return $this->instance->lighten($percent);
	}

	function is_light() {
		return $this->instance->isLight();
	}

	function is_dark() {
		return $this->instance->isDark();
	}

	function complementary() {
		return $this->instance->complementary();
	}

	function hex() {
		return $this->instance->getHex();
	}

	function hsl() {
		return $this->instance->getHsl();
	}

	function rgb() {
		return $this->instance->getRgb();
	}

	function gradient($percent=5) {
		return $this->instance->makeGradient($percent);
	}

}
