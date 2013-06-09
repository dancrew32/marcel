<?
class controller_phonetest extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Phone Home');
		parent::__construct($o);
   	}
 
	function phone() {
		auth::only(['phone']);
		$test_phone_number = '5555555555';
		//phone::text($test_phone_number, 'Hey there, Marcel!');

		$uri = BASE_URL . route::get('Twilio Read');
		$a = phone::call($test_phone_number, $uri);

		//phone::hangup($a);
	}

	function program() {
		# allow only Twilio's servers 
		auth::check(phone::is_twilio()); 

		$p = phone::program();
		$p->say("Hey there, Marcel");
		$p->hangup();
		die($p); # Twilio reads XML
	}
}
