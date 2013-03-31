<?
require_once(dirname(__FILE__).'/inc.php');

$ok = true;
$name = strtolower(gets("Enter Controller name:"));

$boilerplate ="<?
class controller_{$name} extends controller_base {
	function index() {
		if (!auth::is_admin()) return app::redir('/');	
	}
}";

$script_name = "{$name}.php";
$full_script_path = CONTROLLER_DIR."/{$script_name}";

$exists = file_exists($full_script_path);
if ($exists)
	return red("Controller exists.\n");

$ok = file_put_contents($full_script_path, $boilerplate);
if ($ok)
	green("Successfully created controller: {$script_name}\n");
else
	red("WRITE FAIL\n");
