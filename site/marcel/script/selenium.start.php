<?
require_once(dirname(__FILE__).'/inc.php');

root_plz();

yellow("Loading selenium in xvfb server (15 seconds)...\n");
browser::start_selenium() ? ok("Started Selenium http://". BASE_URL .":4444/wd/hub") : fail();
