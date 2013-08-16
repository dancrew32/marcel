<?
require_once(dirname(__FILE__).'/inc.php');

$config = config::$setting;
root_plz();

yellow("Loading selenium in xvfb server (15 seconds)...\n");
browser::start_selenium() ? ok("Started Selenium http://{$config['base_url']}:4444/wd/hub") : fail();
