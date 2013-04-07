<?

# Time
date_default_timezone_set('America/Los_Angeles');

# Directories
if (!defined('ROOT_DIR'))
	define('ROOT_DIR', realpath(dirname(dirname(__FILE__))));

# App
define('APP_NAME', 'Marcel');
define('ENV', 'DEV');
define('SALT', '<your salt>');
define('SESSION_NAME', '<your session name>');

# DB
define('DB_USER', '<user>');
define('DB_PASS', '<pass>');
define('DB_HOST', 'localhost');
define('DB_DB', '<dbname>');
define('DB_DIR', ROOT_DIR.'/db');
define('SCHEMA_DIR', DB_DIR.'/schema');
define('DUMP_DIR', DB_DIR.'/dump');

# System
define('DEBUG', true);
define('CACHE_BUST', false);
if (DEBUG)
	$DEBUG_QUERIES = [];
define('START_TIME', microtime(true));
define('CLI', PHP_SAPI == 'cli');

# Class
define('CLASS_DIR', ROOT_DIR.'/class');
define('CONTROLLER_DIR', ROOT_DIR.'/controller');
define('MODEL_DIR', ROOT_DIR.'/model');

# View
define('VIEW_DIR', ROOT_DIR.'/view');
define('PARTIAL_DIR', VIEW_DIR.'/partial');
define('LAYOUT_DIR', VIEW_DIR.'/layout');

# Vendor
define('VENDOR_DIR', ROOT_DIR.'/vendor');

# Script
define('SCRIPT_DIR', ROOT_DIR.'/script');

# Temporary and Cache
define('TMP_DIR', ROOT_DIR.'/tmp');
define('IMAGECACHE_DIR', TMP_DIR.'/imagecache');

# Assets (Absolute)
define('HTML_DIR', ROOT_DIR.'/html');
define('IMAGE_DIR', HTML_DIR.'/img');

# Assets (Relative)
define('CSS_DIR', '/css');
define('JS_DIR',  '/js');

# Active Record
require_once VENDOR_DIR.'/activerecord/ActiveRecord.php';
ActiveRecord\Config::initialize(function($cfg) {
	$cfg->set_model_directory(MODEL_DIR);
	$cfg->set_connections([
		'default' => 'mysql://'. DB_USER .':'. DB_PASS .'@'. DB_HOST .'/'. DB_DB,
	]);
	$cfg->set_default_connection('default');
});

# App Autoload
spl_autoload_register('clsload');
function clsload($class_name) {
	$file = CLASS_DIR ."/{$class_name}.php";
	if (file_exists($file))
		require_once $file;
}

# Core
require_once CLASS_DIR.'/helper.php';
require_once CONTROLLER_DIR.'/base.php';
require_once CLASS_DIR.'/app.php';
require_once ROOT_DIR.'/routes.php';
app::run();
