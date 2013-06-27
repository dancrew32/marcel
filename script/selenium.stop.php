<?
require_once(dirname(__FILE__).'/inc.php');

root_plz();

$pid = browser::kill_selenium();
if ($pid)
	ok("Killed old selenium pid {$pid}");
else 
	fail("No selenium was found");
