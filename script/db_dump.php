<?
require_once(dirname(__FILE__).'/inc.php');

yellow("Dumping ". DB_NAME ."\n");
$dt = date('Y-m-d H:i:s');
$file = DB_NAME.".{$dt}.sql";
$cmd = "mysqldump --user=". DB_USER ." --password=". DB_PASS; 
$cmd .= " --host=". DB_HOST ." ". DB_NAME ." > '". DUMP_DIR ."/{$file}'";
shell_exec($cmd);
green("Dump Complete: {$file}\n");

