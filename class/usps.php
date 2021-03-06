<?
class usps {

	static function address() {
		require_once config::$setting['vendor_dir'] .'/usps/USPSAddress.php';
		return new USPSAddress;
	}

	static function address_verify() {
		require_once config::$setting['vendor_dir'] .'/usps/USPSAddressVerify.php';
		$api = api::get_key('usps');
		$verify = new USPSAddressVerify($api['username']);
		$verify->setTestMode(ENV == 'DEV');
		return $verify;
	}

	static function city_state() {
		require_once config::$setting['vendor_dir'] .'/usps/USPSCityStateLookup.php';
		$api = api::get_key('usps');
		$verify = new USPSCityStateLookup($api['username']);
		$verify->setTestMode(ENV == 'DEV');
		return $verify;
	}

	static function package() {
		require_once config::$setting['vendor_dir'] .'/usps/USPSRate.php';
		$package = new USPSRatePackage;
		return $package;
	}

	static function rate() {
		require_once config::$setting['vendor_dir'] .'/usps/USPSRate.php';
		$api = api::get_key('usps');
		$rate = new USPSRate($api['username']);
		$rate->setTestMode(ENV == 'DEV');
		return $rate;
	}

	static function tracking() {
		require_once config::$setting['vendor_dir'] .'/usps/USPSTrackConfirm.php';
		$api = api::get_key('usps');
		$tracking = new USPSTrackConfirm($api['username']);
		$tracking->setTestMode(ENV == 'DEV');
		return $tracking;
	}

	static function zip() {
		require_once config::$setting['vendor_dir'] .'/usps/USPSZipCodeLookup.php';
		$api = api::get_key('usps');
		$zipcode = new USPSZipCodeLookup($api['username']);
		$zipcode->setTestMode(ENV == 'DEV');
		return $zipcode;
	}

}
