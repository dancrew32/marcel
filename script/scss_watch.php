<?
require_once(dirname(__FILE__).'/inc.php');

$compass_exists = shell_exec('which compass');
if (!isset($compass_exists{0})) {
	red("You need to install SASS & Compass.\n");
	return red("sudo gem install compass\n");
}
green("Watching scss for changes\n");
return system('compass watch '. ROOT_DIR);
