<?
require_once(dirname(__FILE__).'/inc.php');
yellow("Optimizing Tables in ". DB_NAME ."\n");
$cmd = "mysqlcheck -o ". DB_NAME;
system($cmd);
green("Done.\n");
