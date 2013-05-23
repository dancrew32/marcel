<?
require_once(dirname(__FILE__).'/inc.php');

$ok = true;
$db_name = gets("Enter database name:");
$db_user = gets("Enter database user:");
$db_host = gets("Enter database host: (localhost)");
if (!isset($db_host{0}))
	$db_host = 'localhost';
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
replace_line_with_match($index_file, "define('DB_HOST'", "define('DB_HOST', '{$db_host}');\n");
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


ActiveRecord\Config::initialize(function($cfg) {
	$cfg->set_model_directory(MODEL_DIR);
	$cfg->set_connections([
		'default' => "mysql://{$GLOBALS['db_user']}:{$GLOBALS['db_pass']}@{$GLOBALS['db_host']}/{$GLOBALS['db_name']}",
	]);
	$cfg->set_default_connection('default');
});

$seed = strtolower(gets("Seed database with defaults? [Y/n]"));
if ($seed != 'n') {
	$seed_models = [
		'User_Type',
		'Feature',
		'User_Permission',
	];
	green("Now Seeding...\n");
	foreach ($seed_models as $sm) {
		echo " seeding {$sm}...";
		try {
			$sm::seed();
			green(" Done.\n");
		} catch (Exception $e) {
			red(" FAIL.\n");	
		}
	}
	green("Seeding Complete.\n");
}

$user_create = strtolower(gets("Create first user? [Y/n]"));
if ($user_create != 'n')
	include_once SCRIPT_DIR.'/create_user.php';

green("DB INIT Complete.\n");

