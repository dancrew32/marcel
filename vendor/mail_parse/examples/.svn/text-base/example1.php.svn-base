<?php


require_once('../MimeMailParser.class.php');

$path = '../data/attachments-mail.txt';
$Parser = new MimeMailParser();
$Parser->setText(file_get_contents($path));
//$Parser->setPath($path);

$to = $Parser->getHeader('to');
$delivered_to = $Parser->getHeader('delivered-to');
$from = $Parser->getHeader('from');
$subject = $Parser->getHeader('subject');
$text = $Parser->getMessageBody('text');
$html = $Parser->getMessageBody('html');
$attachments = $Parser->getAttachments();

// dump it so we can see
var_dump(
	$to,
	$delivered_to,
	$from,
	$subject,
	$text,
	$html,
	$attachments
);

var_dump($attachments[0]->content, $attachments[0]->extension);

?>