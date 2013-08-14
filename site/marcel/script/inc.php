<?
if (!defined('ROOT_DIR'))
	define('ROOT_DIR', realpath(dirname(dirname(dirname(dirname(__FILE__))))));
if (!defined('SITE_DIR'))
	define('SITE_DIR', realpath(dirname(dirname(__FILE__))));
require_once SITE_DIR.'/public/index.php';
require_once ROOT_DIR.'/class/cli_helper.php';
if (CLI) {
	ini_set('mysql.connect_timeout', 300);
	ini_set('default_socket_timeout', 300);
	db::init();
}

