<? 
require_once "../../../class/config.php";

$settings = [

	'root_dir' => realpath(dirname(dirname(dirname(dirname(__FILE__))))),
	'site_dir' => realpath(dirname(dirname(__FILE__))),

	# APP
	'base_url'      => "site.com",
	'admin_email'   => "admin@example.com",
	'app_name'      => "Marcel",
	'salt'          => '<yourGreaterThan21CharacterSalt>',
	'session_name'  => 'session_name',

	# DB
	'db_user' => 'user',
	'db_pass' => 'pass',
	'db_host' => 'host',
	'db_name' => 'name',

];

config::init($settings);
app::run();
