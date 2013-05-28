<? require_once dirname(__FILE__).'/inc.php';

$data = file_get_contents('php://stdin');

$mp        = new mail_parse($data);
$from      = $mp->from();
$from_name = $mp->from_name();
$subject   = $mp->subject();

//print_r($mp->parts());

$m = new mail;
$m->from      = 'test@example.com';
$m->from_name = 'Inbound message';
$m->add_reply_to($from, $from_name);
$m->subject   = "Inbound message: \"{$subject}\"";
$m->add_address('test@example.com', 'Your Name');
$m->body = r('email', 'incoming_test', [
	'to'        => $mp->to(),
	'from'      => $from,
	'from_name' => $from_name,
	'cc'        => $mp->cc(),
	'subject'   => $subject,
	'body'      => $mp->body(),
]);
$m->queue();

