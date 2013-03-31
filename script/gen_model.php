<?
require_once(dirname(__FILE__).'/inc.php');

$ok = true;
$name = ucfirst(gets("Enter Model name (e.g. My_Name):"));
$table = strtolower($name).'s';

$boilerplate ="<?
class {$name} extends ActiveRecord\Model {
	static \$table_name = '{$table}';
	static \$has_one = [
		// [ 'stats', 'class_name' => 'Cat_Stat' ]	
		// [ 'foos', 'through' => 'bars' ]	
	];
	static \$has_many = [
		// [ 'cats' ]	
		// [ 'foos', 'through' => 'bars' ]	
	];
	static \$belongs_to = [
		// [ 'cheeses' ]	
	];
}";

$script_name = "{$name}.php";
$full_script_path = MODEL_DIR."/{$script_name}";

$exists = file_exists($full_script_path);
if ($exists)
	return red("Model exists.\n");

$ok = file_put_contents($full_script_path, $boilerplate);
if ($ok)
	green("Successfully created model: {$script_name}\n");
else
	red("WRITE FAIL\n");
