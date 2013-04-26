<?
require_once VENDOR_DIR.'/mailer/class.phpmailer.php';
//require_once VENDOR_DIR.'/mailer/class.smtp.php';
//require_once VENDOR_DIR.'/mailer/class.pop3.php';
class mail extends PHPMailer {
	function __construct() {
		//$this->IsSMTP();                                      // Set mailer to use SMTP
		//$this->Host = 'smtp.example.com;smtp2.example.com';   // Specify main and backup server
		//$this->SMTPAuth = true;                               // Enable SMTP authentication
		//$this->Username = 'jswan';                            // SMTP username
		//$this->Password = 'secret';                           // SMTP password
		//$this->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
		//$this->SMTPDebug = 1;                                 // Debug

		//$this->From = 'from@example.com';
		//$this->FromName = 'Mailer';
		//$this->AddAddress('josh@example.net', 'Josh Adams');  // Add a recipient
		//$this->AddAddress('ellen@example.com');               // Name is optional
		//$this->AddReplyTo('info@example.com', 'Information');
		//$this->AddCC('cc@example.com');
		//$this->AddBCC('bcc@example.com');

		//$this->WordWrap = 50;                                 // Set word wrap to 50 characters
		//$this->AddAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$this->AddAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		//$this->IsHTML(true);                                  // Set email format to HTML

		//$this->Subject = 'Here is the subject';
		//$this->Body    = 'This is the HTML message body <b>in bold!</b>';
		//$this->AltBody = 'This is the body in plain text for non-HTML mail clients';
		//$this->Send();
	}	

	function Queue() {
		Worker::add([
			'class'  => 'mail',
			'method' => 'process',
			'args' => [
				'email' => $this, #serialize email
			],
		]);
	}

	static function process($args) {
		$email = take($args, 'email');
		return $email->Send();
	}
}
