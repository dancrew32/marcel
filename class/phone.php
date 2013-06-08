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

	static $user_agents = [
		'twilio' => 'TwilioProxy'
	];

	# ghetto send text
	static function text_address($options) {
		$options = array_merge([
			'phone'    => '',
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

	static function text($to, $text) {
		if (!isset($text{0})) return false;

		require_once VENDOR_DIR.'/twilio/Services/Twilio.php';
		$api = api::get_key('twilio');
		$client = new Services_Twilio($api['key'], $api['secret']);

		$message = $client->account->sms_messages->create(
			$api['phone'], # from
			$to, # to
			$text
		);
		return $message;
	}

	static function program() {
		require_once VENDOR_DIR.'/twilio/Services/Twilio.php';
		return new Services_Twilio_Twiml();
	}

	static function call($to, $program, array $params=[]) {
		if (!$program) return false;
		require_once VENDOR_DIR.'/twilio/Services/Twilio.php';
		$api = api::get_key('twilio');
		$client = new Services_Twilio($api['key'], $api['secret']);

		$call = $client->account->calls->create(
			$api['phone'], # from
			$to, # to
			$program, # twiml
			$params
		);
		return $call;
	}

	static function hangup($call) {
		$api = api::get_key('twilio');
		$client = new Services_Twilio($api['key'], $api['secret']);
		$call->update([
			'Status' => 'completed'
		]);
		return $call;
	}

	static function is_twilio() {
		return strpos(USER_AGENT, self::$user_agents['twilio']) !== false;
	}

}
