<?
class controller_phonetest extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Phone Home');
		parent::__construct($o);
   	}

	function phone() {
		auth::only(['phone']);

		$phone = '2098145255';

		# Text
		$message = 'Hey there, Marcel!';
		phone::queue_text($phone, $message);
		pp('Text queued.');

		# Call
		$program_url = route::get_absolute('Twilio Read');
		phone::queue_call($phone, $program_url);
		pp('Call queued.');
	}

	function program() {
		auth::check(phone::is_twilio()); 

		$record_url = route::get_absolute('Twilio Record');
		$text = fake::paragraph(rand(2,3)); # random text

		$p = phone::program();
		$p->say($text);
		$p->record([
			'action'    => $record_url, 
			'maxLength' => 10,
		]);
		die($p); # Twilio reads XML
	}

	function recording() {
		auth::check(phone::is_twilio()); 

		$data = $_REQUEST['RecordingUrl'];
		$p = phone::program();
		$p->say('Thanks for talking.');
		$p->play($data);
		$p->say('that was funny. goodbye.');
		$p->hangup();
		die($p); # Twilio reads XML
	}
}
