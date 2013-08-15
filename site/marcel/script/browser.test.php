<?
require_once(dirname(__FILE__).'/inc.php');

yellow("Starting Session...\n");
$b = new browser('firefox');
ok();

pr($b->can_do());

$width  = 1024;
$height = 1024;
yellow("Resizing window to {$width}x{$height}...\n");
$b->set_size($width, $height);
ok();

/*
$site = 'http://twitter.github.io/bootstrap';
yellow("Navigating to {$site}...\n");
$b->open($site)->wait(3)->url_ok($site) ? ok("Loaded") : fail();

yellow("Capturing h1's\n");
$h1 = $b->find('h1');
foreach ($h1 as $k => $h) {
	ok($h->text());
	$b->screenshot_element($h, IMAGE_DIR."/h1-{$k}.png");
	ok('http://'. BASE_URL ."/img/h1-{$k}.png");
}
*/

/*
# TAKE RESPONSIVE SNAPSHOTS
yellow("Taking screenshots...\n");
$sizes = [
	'a' => ['width' => 1024, 'height' => 720],
	'b' => ['width' => 720, 'height' => 600],
	'c' => ['width' => 320, 'height' => 400],
];
foreach ($sizes as $k => $size) {
	$b->set_size($size['width'], $size['height']);
	$file = IMAGE_DIR."/browser-{$k}.png";
	$b->screenshot($file);
	ok('http://'. BASE_URL ."/img/browser-{$k}.png");
}
 */

yellow("Exiting...\n");
$b->close();
ok("DONE");
