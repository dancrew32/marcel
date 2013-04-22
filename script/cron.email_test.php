<?
require_once(dirname(__FILE__).'/inc.php');

$m = new mail;
$m->From = 'you@example.com';
$m->FromName = 'Marcel';
$m->AddAddress('user@example.com', 'Example User');
$m->Subject = "Queue Test";
$m->Body = "This concludes the test!";
Worker::add([
	'class'  => 'mail',
	'method' => 'queue',
	'args'   => [
		'email' => $m, #serialize email
	],
]);
