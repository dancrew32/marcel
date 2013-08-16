<?
require_once(dirname(__FILE__).'/inc.php');

$config = config::$setting;

yellow("Updating {$config['db_name']} schemas in {$config['schema_dir']}\n");

$tables = db::get_array('show tables');
foreach ($tables as $k => $row) {
	$table = $row["Tables_in_{$config['db_name']}"];
	$file = "{$table}.sql";
	$cmd = "mysqldump --single-transaction --no-data --user={$config['db_user']} --password={$config['db_pass']}";
	$cmd .= " --host={$config['db_host']} {$config['db_name']} {$table} > '{$config['schema_dir']}/{$file}'";
	shell_exec($cmd);
	$contents = file_get_contents("{$config['schema_dir']}/{$file}");
	$contents = preg_replace('/ AUTO_INCREMENT=[0-9]+/', '', $contents);
	//$contents = preg_replace('/CONSTRAINT.*/', '', $contents);
	$contents = preg_replace('/\/\*.*/m', '', $contents);
	$contents = preg_replace('/\-\-.*/m', '', $contents);
	$contents = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $contents);
	$contents = preg_replace("/CREATE TABLE/", "\nCREATE TABLE", $contents);
	file_put_contents("{$config['schema_dir']}/{$file}", $contents);
	green("Schema updated: {$file}\n");
}

green("Done with updates.\n");
green("git commit -am \"Schema Update\"\n");
