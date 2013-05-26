<?
class controller_shipping extends controller_base {
	function __construct($o) {
		$this->root_path = app::get_path('Shipping Home');
		auth::only(['shipping']);
		parent::__construct($o);
   	}

	function main() {

		# ADDRESS VERIFICATION
		$address = usps::address();
		$address->setFirmName('Apartment');
		$address->setApt('100');
		$address->setAddress('2130 Bay Street');
		$address->setCity('San Francisco');
		$address->setState('CA');
		$address->setZip5(94113);
		$address->setZip4('');

		$verify = usps::address_verify();
		$verify->addAddress($address);

		pp($verify->verify());
		pp($verify->getArrayResponse());
		pp($verify->isError());
		if ($verify->isSuccess())
			pp('Done');
		else
			pp('Error: ' . $verify->getErrorMessage());


		# CITY STATE LOOKUP
		$verify = usps::city_state();
		$verify->addZipCode('91730'); # zip code for city and state
		pp($verify->lookup());
		pp($verify->getArrayResponse());
		if ($verify->isSuccess())
			pp('Done');
		else
			pp('Error: ' . $verify->getErrorMessage());


		# DOMESTIC RATE
		$package = usps::package(); # order specific
		$package->setService(USPSRatePackage::SERVICE_FIRST_CLASS);
		$package->setFirstClassMailType(USPSRatePackage::MAIL_TYPE_LETTER);
		$package->setZipOrigination(91601);
		$package->setZipDestination(91730);
		$package->setPounds(0);
		$package->setOunces(3.5);
		$package->setContainer('');
		$package->setSize(USPSRatePackage::SIZE_REGULAR);
		$package->setField('Machinable', true);

		$rate = usps::rate();
		$rate->addPackage($package);

		pp($rate->getRate());
		pp($rate->getArrayResponse());
		if ($rate->isSuccess())
			pp('Done');
		else
			pp('Error: ' . $rate->getErrorMessage());


		# TRACKING
		$tracking = usps::tracking();
		$tracking->addPackage("EJ958083578US");
		pp($tracking->getTracking());
		pp($tracking->getArrayResponse());

		if ($tracking->isSuccess())
			pp('Done');
		else
			pp('Error: ' . $tracking->getErrorMessage());


		# ZIP CODE LOOKUP
		$address = usps::address();
		$address->setFirmName('Apartment');
		$address->setApt('100');
		$address->setAddress('9200 Milliken Ave');
		$address->setCity('Rancho Cucomonga');
		$address->setState('CA');

		$zip = usps::zip();
		$zip->addAddress($address);

		pp($zip->lookup());
		pp($zip->getArrayResponse());
		if ($zip->isSuccess())
			pp('Done');
		else
			pp('Error: ' . $zip->getErrorMessage());
	}
}
