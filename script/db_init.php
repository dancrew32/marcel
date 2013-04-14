<?
require_once(dirname(__FILE__).'/inc.php');

$ok = true;
$db_name = gets("Enter database name:");
$db_user = gets("Enter database user:");
$db_pass = prompt_silent("Enter database password:");


# CREATE DATABASE
$create = strtolower(gets("Shall I create database `{$db_name}`? [y/N]"));
if ($create != 'y')
	return red("Creation cancelled. Now Exiting.");
$pdo = new PDO('mysql:host=localhost', $db_user, $db_pass);
$ok = $pdo->exec("CREATE DATABASE IF NOT EXISTS {$db_name}");
if (!$ok) return red("FAIL");


# UPDATE INDEX.PHP
$index_file = PUBLIC_DIR.'/index.php';	
replace_line_with_match($index_file, "define('DB_USER'", "define('DB_USER', '{$db_user}');\n");
replace_line_with_match($index_file, "define('DB_PASS'", "define('DB_PASS', '{$db_pass}');\n");
//replace_line_with_match($index_file, "define('DB_HOST'", "define('DB_HOST', '{$db_host}');\n");
replace_line_with_match($index_file, "define('DB_NAME'", "define('DB_NAME', '{$db_name}');\n");
green("DB Constants updated in {$index_file}.\n");


# APPLY SCHEMAS
$apply_schemas = strtolower(gets("Apply schemas? [Y/n]"));
if ($apply_schemas != 'n') {
	$schemas = glob(SCHEMA_DIR.'/*.sql');
	green("Applying Schemas:\n");
	$pdo = new PDO("mysql:host=localhost;dbname={$db_name}", $db_user, $db_pass);
	foreach ($schemas as $sch) {
		$file = util::explode_pop('/', $sch);
		echo " Applying {$file}\n";
		$sql = file_get_contents($sch);	
		$pdo->exec($sql);
	}
	green("Done applying schemas.\n");
	$pdo = null;
}

green("DB INIT Complete.\n");
