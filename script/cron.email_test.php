<?
require_once(dirname(__FILE__).'/inc.php');

$m = new mail;
$m->from = 'you@example.com';
$m->from_name = 'Marcel';
$m->add_address('user@example.com', 'Example User');
$m->subject = "Queue Test";
$m->body = "This concludes the test!";
$m->queue();
