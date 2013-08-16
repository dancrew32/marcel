#!/usr/bin/php
<? 
array_shift($argv);
$site = array_shift($argv);
if (!$site)
	die("Must have a <site> parameter!\ne.g. \"./m <site> <command>\n\"");
require_once(dirname(__FILE__)."/site/{$site}/script/inc.php");
$search = implode('', $argv);
$is_search = isset($search{0});
$scripts = glob(config::$setting['script_dir'].'/*.php');
$use = false;

foreach ($scripts as $k => $s) {
	$name = util::explode_pop('/', $s);	
	if ($is_search) {
		$name = preg_replace('/[_\-\.]/i', '', $name);
		$q = '/.*'.preg_quote($search, '/').'.*/';
		if (!preg_match($q, $name)) continue;
		$use = $k;
		break;
	}
	echo "{$k}. {$name}\n"; 
}

if (!$is_search)
	$use = gets("Enter number for script to run");

if (!is_file($scripts[$use])) 
	return red("No match\n");
include_once "{$scripts[$use]}";
