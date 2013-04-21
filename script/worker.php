<?
require_once(dirname(__FILE__).'/inc.php');

while(true) {
	$ws = Worker::all();
	foreach ($ws as $w) {
		$w->run();	
	}
	sleep(1);
}
