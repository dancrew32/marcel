<?
require_once(dirname(__FILE__).'/inc.php');

//include_once SCRIPT_DIR.'/selenium.start.php';
//sleep(4); # boot time
yellow("Starting Session...\n");
$b = new browser();
$s = $b->session();
ok();

#pr($s->capabilities());

$width  = 1024;
$height = 1024;
yellow("Resizing window to {$width}x{$height}...\n");
$s->window()->postSize(['width' => $width, 'height' => $height]);
ok();

$site = 'http://google.com';
yellow("Navigating to {$site}...\n");
$s->open($site);
$s->implicitlyWait(3);
similar_text($site, $s->url(), $percent);
$percent > 50 ? ok("Loaded") : fail();

yellow("Capturing h1's\n");
$h1 = $s->elements('tag name', 'h1');

yellow("Taking screenshot...\n");
$img  = $s->screenshot();
$data = base64_decode($img);
$file = IMAGE_DIR.'/browser.png';
if (file_exists($file))
	unlink($file);
$success = file_put_contents($file, $data);
ok('http:'. BASE_URL .'/img/browser.png');

yellow("Exiting...\n");
$s->close();
ok("DONE");
