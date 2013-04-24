<?
class phone {
	
	static $phone_domains = [
		'AT&T'          => 'txt.att.net',
		'Verizon'       => 'vtext.com',
		'T-Mobile'      => 'tomomail.net',
		'Sprint PCS'    => 'messaging.sprintpcs.com',
		'Virgin Mobile' => 'vmobl.com',
		'US Cellular'   => 'email.uscc.net',
		'Nextel'        => 'messaging.nextel.com',
		'Boost'         => 'myboostmobile.com',
		'Alltel'        => 'alltel.com',
	];

	static function text_address($options) {
		$options = array_merge([
			'phone'   => '',
			'provider' => '',
		], $options);	


		$number = preg_replace('/[^0-9]/', '', $options['number']);
		if (!strlen($number) >= 10)
			return false;
		$domain = take(self::$phone_domains, $options['provider'], false);
		if (!$domain) 
			return false;
		return "{$number}@{$domain}";
	}

}
