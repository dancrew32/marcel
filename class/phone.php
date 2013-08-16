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

/*
 * TEXT MESSAGE
 */
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

	# Twilio text message
	static function text($to, $text) {
		if (!isset($text{0})) return false;

		require_once config::$setting['vendor_dir'].'/twilio/Services/Twilio.php';
		$api = api::get_key('twilio');
		$client = new Services_Twilio($api['key'], $api['secret']);

		$message = $client->account->sms_messages->create(
			$api['phone'], # from
			$to, # to
			$text
		);
		return $message;
	}

	static function handle_queue_text($args) {
		$to = take($args, 'to');
		$text = take($args, 'text');
		return self::text($to, $text);
	}

	# worker
	static function queue_text($to, $text) {
		return Worker::add([
			'class'  => __CLASS__,
			'method' => 'handle_queue_text',
			'args' => [
				'to'   => $to,
				'text' => $text,
			],
		]);
	}

/*
 * TwiML builder
 */
	static function program() {
		require_once config::$setting['vendor_dir'].'/twilio/Services/Twilio.php';
		return new Services_Twilio_Twiml();
	}

/*
 * Phone Call
 */
	static function call($to, $program, array $params=[]) {
		if (!$program) return false;
		require_once config::$setting['vendor_dir'].'/twilio/Services/Twilio.php';
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

	static function handle_queue_call($args) {
		$to = take($args, 'to');
		$program = take($args, 'program');
		$params = unserialize(take($args, 'params'));
		return self::call($to, $program, $params);
	}

	# worker
	static function queue_call($to, $program, array $params=[]) {
		return Worker::add([
			'class'  => __CLASS__,
			'method' => 'handle_queue_call',
			'args' => [
				'to' => $to,
				'program' => $program,
				'params' => serialize($params),
			],
		]);
	}

	static function hangup($call) {
		$call->update([
			'Status' => 'completed'
		]);
		return $call;
	}

	static function is_twilio() {
		return strpos(USER_AGENT, self::$user_agents['twilio']) !== false;
	}

}
