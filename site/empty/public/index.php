<? 
if (!defined('ROOT_DIR')) 
	define('ROOT_DIR', realpath(dirname(dirname(dirname(dirname(__FILE__))))));

if (!defined('SITE_DIR')) 
	define('SITE_DIR', realpath(dirname(dirname(__FILE__))));

# Time
date_default_timezone_set('America/Los_Angeles');

define('CONFIG_DIR', SITE_DIR.'/config');

# Environment
if (!defined('ENV'))
   	define('ENV', (getenv('ENV') ? getenv('ENV') : 'DEV'));

# System
define('DEBUG', ENV == 'DEV');
define('CACHE_BUST', true);
define('START_TIME', microtime(true));
define('CLI', PHP_SAPI == 'cli');
define('AJAX', (
	isset($_SERVER['HTTP_X_REQUESTED_WITH']{0}) 
	&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'));

# App Specific
define('BASE_URL', "//site.com");
define('ADMIN_EMAIL', "admin@example.com");
define('APP_NAME', 'Marcel');
define('SALT', '<yourGreaterThan21CharacterSalt>'); # must be creater than 21 chars
define('SESSION_NAME', 'session_name');
$IP_WHITELIST = [ ];

# DB
define('DB_USER', 'marcel');
define('DB_PASS', 'testing');
define('DB_HOST', 'localhost');
define('DB_NAME', 'marcel');
define('DB_DIR', SITE_DIR.'/db');
define('SCHEMA_DIR', DB_DIR.'/schema');
define('DUMP_DIR', DB_DIR.'/dump');

# Class
define('CLASS_DIR', ROOT_DIR.'/class');
define('CONTROLLER_DIR', SITE_DIR.'/controller');
define('MODEL_DIR', ROOT_DIR.'/model');

# View
define('VIEW_DIR', SITE_DIR.'/view');
define('LAYOUT_DIR', VIEW_DIR.'/layout');

# Vendor
define('VENDOR_DIR', ROOT_DIR.'/vendor');

# Script
define('SCRIPT_DIR', SITE_DIR.'/script');

# Font
define('FONT_DIR', ROOT_DIR.'/font');

# Temporary and Cache
define('TMP_DIR', ROOT_DIR.'/tmp');
define('IMAGECACHE_DIR', TMP_DIR.'/imagecache');

# Assets (Absolute)
define('PUBLIC_DIR', SITE_DIR.'/public');
define('IMAGE_DIR', PUBLIC_DIR.'/img');

# Assets (Relative)
define('CSS_DIR', '/css');
define('JS_DIR',  '/js');

# App Autoload
spl_autoload_register('clsload');
function clsload($class_name) {
	$file = CLASS_DIR ."/{$class_name}.php";
	if (is_file($file))
		require_once $file;
}

# Core
require_once CLASS_DIR.'/helper.php';
app::run();
