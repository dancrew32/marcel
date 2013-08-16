<?
require_once dirname(__FILE__).'/inc.php';

$config = config::$setting;


# INITIALIZE PROGRAM
$p = new program;

# Site (which site to apply this to)
$p->option([
	'short'    => 's',
	'long'     => 'site',
	'value'    => true,
	'help'     => "Site (/site/<site> folder name to apply db_init to)",
	'required' => true,
]);

# Name (Name of your future controller)
$p->option([
	'short' => 'n',
	'long'  => 'name',
	'value' => true,
	'help'  => "Controller name (of the controller you're about to make)",
]);

# Index File
$p->option([
	'short'   => 'i',
	'long'    => 'index',
	'value'   => true,
	'help'    => 'Site index file (referenced by .htaccess)',
	'default' => "{$config['public_dir']}/index.php",
]);

# Database Name
$p->option([
	'short'   => 'D',
	'long'    => 'db_name',
	'value'   => true,
	'help'    => 'Name of your database',
]);

//# Database User
$p->option([
	'short'   => 'U',
	'long'    => 'db_user',
	'value'   => true,
	'help'    => 'User for your database',
]);

# Database Host
$p->option([
	'short'   => 'H',
	'long'    => 'db_host',
	'value'   => true,
	'type'    => 'text',
	'help'    => 'Host for your database',
]);


# HELP MENU ABORT
if ($p->get('h')) die($p->help());


yellow(ascii::marcel().'
------------
| DB INIT! |
------------
');


# GET DB CREDENTIALS
$db = [
	'name' => $p->getif('D', "Enter your database name:"),
	'user' => $p->getif('U', "Enter your database user:"),
	'host' => $p->getif('H', "Enter your database host: (blank for localhost)"),
];
if (!isset($db['host']))
	$db['host'] = 'localhost';
$db['pass'] = prompt_silent("Enter your database password:");



# CREATE DATABASE
$create = gets("Shall I create database `{$db['name']}`? [Y/n]", ['lower']);
if ($create == 'n')
	return red("Creation cancelled. Now Exiting.");
try {
	$pdo = new PDO('mysql:host=localhost', $db['user'], $db['pass']);
} catch (Exception $e) {
	return fail('MySQL connect failure. Wrong user/password/host?');
}
$p->check($pdo->exec("CREATE DATABASE IF NOT EXISTS {$db['name']}"));
if (!$p->ok()) 
	return fail("MySQL table create failure. Does {$db['user']} have the correct permissions?");



# UPDATE INDEX FILE WITH CONSTANTS
$index_file = $p->get('i'); # default 
foreach ($db as $k => $v) {
	util::replace_line_with_match(
		$index_file, "'db_{$k}'", "\'db_{$k}' => '{$v}'),\n"
	);
}
ok("DB Constants updated in {$index_file}.");



# APPLY SCHEMAS
$apply_schemas = gets("Apply schemas? [Y/n]", ['lower']);
if ($apply_schemas != 'n') {
	$schemas = glob("{$config['schema_dir']}/*.sql");
	ok("Applying Schemas:");
	$pdo = new PDO("mysql:host=localhost;dbname={$db['name']}", $db['user'], $db['pass']);
	foreach ($schemas as $sch) {
		$file = util::explode_pop('/', $sch);
		echo " Applying {$file}\n";
		$sql = file_get_contents($sch);	
		$pdo->exec($sql);
	}
	ok("Done applying schemas.");
	$pdo = null;
}


# ESTABLISH ACTIVE RECORD CONNECTION
ActiveRecord\Config::initialize(function($cfg) use ($db) {
	$default_key = 'default'; # TODO: make this key an option
	$connections = [
		$default_key => "mysql://{$db['user']}:{$db['pass']}@{$db['host']}/{$db['name']}",
	];
	$cfg->set_model_directory(config::$setting['model_dir']);
	$cfg->set_connections($connections);
	$cfg->set_default_connection($default_key);
});



# ATTEMPT TO SEED DATABASE WITH Model::seed()
$seed = $p->getif('s', "Seed database with defaults? [Y/n]", ['lower']);
if ($seed != 'n') {
	$seed_models = [
		'User_Type',
		'Feature',
		'User_Permission',
	];
	ok("Now Seeding...");
	foreach ($seed_models as $sm) {
		echo " seeding {$sm}...";
		try {
			$sm::seed();
			ok(" Done.");
		} catch (Exception $e) {
			fail(" Unable to seed.\n {$e->getMessage()}\n");	
		}
	}
	ok("Seeding Complete.");
}


# CREATE FIRST USER
$user_create = gets("Create first user? [Y/n]", ['lower']);
if ($user_create != 'n')
	include_once "{$config['script_dir']}/create_user.php";



ok("DB INIT Complete.");
