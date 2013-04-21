<?
require_once(dirname(__FILE__).'/inc.php');

# Send 10 emails
$i = 10;
while ($i--) {
	$m = new mail;
	$m->From = 'you@example.com';
	$m->FromName = 'Marcel';
	$m->AddAddress('user@example.com', 'Example User');
	$m->Subject = "Queue Test {$i}";
	$m->Body = "This concludes test #{$i}!";
	Worker::add([
		'class'  => 'mail',
		'method' => 'queue',
		'args'   => [
			'email' => $m, #serialize email
		],
	]);
}
