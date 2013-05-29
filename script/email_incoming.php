<? require_once dirname(__FILE__).'/inc.php';

$data = file_get_contents('php://stdin');

$mp   = new mail_parse($data);
$to   = $mp->to();
$from = $mp->from();
$hash = util::explode_shift('@', $to);
$subject = $mp->subject();

$thread = Email_Thread::latest([
	'hash' => $hash,
	'from' => $from,
]);

$mail_data = [
	'to'        => $to,
	'hash'      => $hash,
	'from'      => $from,
	'from_name' => $mp->from_name(),
	'cc'        => $mp->cc(),
	'subject'   => $subject,
	'body'      => $mp->body(),
];

Email_Thread::create($mail_data);

//print_r($mp->parts());

$domain     = 'l.danmasq.com';
$to_address = $thread ? $thread->from : 'dancrew32@gmail.com';
$to_name    = $thread ? $thread->from_name : 'Dan Masquelier';

$m = new mail;
$m->from      = "hit-reply@{$domain}";
$m->from_name = APP_NAME;
$m->subject   = "Inbound message: \"{$subject}\"";
$m->body      = r('email', 'incoming_test', $mail_data);

$m->add_address($to_address, $to_name);
$m->add_reply_to("{$hash}@{$domain}", $from_name);

$m->queue();
