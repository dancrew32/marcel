<?
require_once(dirname(__FILE__).'/inc.php');

yellow("Updating ". DB_NAME ." schemas in ". SCHEMA_DIR ."\n");

$tables = db::get_array('show tables');
foreach ($tables as $k => $row) {
	$table = $row['Tables_in_'.DB_NAME];
	$file = "{$table}.sql";
	$cmd = "mysqldump --single-transaction --no-data --user=". DB_USER ." --password=". DB_PASS; 
	$cmd .= " --host=". DB_HOST ." ". DB_NAME ." {$table} > '". SCHEMA_DIR ."/{$file}'";
	shell_exec($cmd);
	$contents = file_get_contents(SCHEMA_DIR."/{$file}");
	$contents = preg_replace('/ AUTO_INCREMENT=[0-9]+/', '', $contents);
	$contents = preg_replace('/\/\*.*/m', '', $contents);
	$contents = preg_replace('/\-\-.*/m', '', $contents);
	$contents = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $contents);
	$contents = preg_replace("/CREATE TABLE/", "\nCREATE TABLE", $contents);
	file_put_contents(SCHEMA_DIR."/{$file}", $contents);
	green("Schema updated: {$file}\n");
}

green("Done with updates.\n");
green("git commit -am \"Schema Update\"\n");
