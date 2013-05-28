<?php

/**
 * To run this example you need to pass in some input via STDIN
 */

require_once('../MimeMailParser.class.php');

$Parser = new MimeMailParser();
$Parser->setStream(STDIN);

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

?>

