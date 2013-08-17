<? 
$site = dirname(dirname(__FILE__));
$root = realpath(dirname(dirname($site)));
require_once "{$root}/class/config.php";

$settings = [ 

    'root_dir' => $root,
    'site_dir' => realpath($site),

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
