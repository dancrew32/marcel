<?
class detect {

	const COOKIE_PREFIX = 'is_';

	private static $instance = null;	

	private static $is = [
		'mobile'  => null,
		'tablet'  => null,
		'ios'     => null,
		'android' => null,
	];

	static function get_instance() {
		if (is_null(self::$instance)) {
			require_once config::$setting['vendor_dir'] .'/mobile_detect/Mobile_Detect.php';
			self::$instance = new Mobile_Detect;
		}
		return self::$instance;	
	}

	static function is_tablet() {
		$c = cookie::get(self::COOKIE_PREFIX.'tablet');
		if ($c !== '') return $c;
		if (is_null(self::$is['tablet'])) {
			cookie::set(self::COOKIE_PREFIX.'tablet', 
				self::$is['tablet'] = self::get_instance()->isTablet() ? 1 : 0, time::ONE_YEAR);
		}
		return self::$is['tablet'];
	}

	static function is_mobile() {
		$c = cookie::get(self::COOKIE_PREFIX.'mobile');
		if ($c !== '') return $c;
		if (is_null(self::$is['mobile'])) {
			cookie::set(self::COOKIE_PREFIX.'mobile', 
				self::$is['mobile'] = (self::get_instance()->isMobile() && !self::is_tablet()) ? 1 : 0,
				time::ONE_YEAR);
		}
		return self::$is['mobile'];
	}

	static function is_ios() {
		$c = cookie::get(self::COOKIE_PREFIX.'ios');
		if ($c !== '') return $c;
		if (is_null(self::$is['ios'])) {
			cookie::set(self::COOKIE_PREFIX.'ios', 
				self::$is['ios'] = (self::get_instance()->isiOS()) ? 1 : 0,
				time::ONE_YEAR);
			if (self::$is['ios']) {
				self::$is['android'] = false;
			}
		}
		return self::$is['ios'];
	}

	static function is_android() {
		if (is_null(self::$is['android'])) {
			cookie::set(self::COOKIE_PREFIX.'andoroid', 
				self::$is['ios'] = (self::get_instance()->isAndroidOS()) ? 1 : 0,
				time::ONE_YEAR);
			if (self::$is['android']) {
				self::$is['ios'] = false;
			}
		}
		return self::$is['android'];
	}

}
