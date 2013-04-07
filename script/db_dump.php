<?
require_once(dirname(__FILE__).'/inc.php');

yellow("Dumping ". DB_DB ."\n");
$dt = date('Y-m-d H:i:s');
$file = DB_DB.".{$dt}.sql";
$cmd = "mysqldump --user=". DB_USER ." --password=". DB_PASS; 
$cmd .= " --host=". DB_HOST ." ". DB_DB ." > '". DUMP_DIR ."/{$file}'";
shell_exec($cmd);
green("Dump Complete: {$file}\n");

