<?
require_once(dirname(__FILE__).'/inc.php');

$ok = true;
$name = ucfirst(gets("Enter Model name (e.g. My_Name):"));
$table = strtolower($name).'s';

$boilerplate ="<?
class {$name} extends model {
	static \$table_name = '{$table}';
	/*
	 * These are just examples. delete what you don't need.
	 * * *
	static \$has_one = [
		[ 'stats', 'class_name' => 'Cat_Stat' ]	
		[ 'foos', 'through' => 'bars' ]	
	];
	static \$has_many = [
		[ 'cats' ],
		[ 'foos', 'through' => 'bars' ],
	];
	static \$belongs_to = [
		[ 'cheeses' ],
	];
	static \$validates_presence_of = [
		['name', 'message' => 'must be added!'],
	];
	static \$validates_size_of = [
		['fieldz', 'is' => 42, 'message' => 'must be exactly 42 chars'],
		['fielda', 'minimum' => 9, 'too_short' => 'must be at least 9 characters long'],
		['fieldb', 'maximum' => 20, 'too_long' => 'is too long!'],
		['fieldc', 'within' => [5-10], 
			'too_short' => 'must be longer than 5 (less than 10)', 
			'too_long' => 'must be less than 10 (greater than 5 though)!'
		],
	];
	static \$validates_inclusion_of = [
		['types', 'in' => ['list', 'of', 'allowed', 'types'], 'message' => 'is invalid'],
	];
	static \$validates_exclusion_of = [
		['password', 'in' => ['list', 'of', 'bad', 'passwords'], 'message' => 'is invalid'],
	];
	static \$validates_numericality_of = [
		['price', 'greater_than' => 0.01],
		['quantity', 'only_integer' => true],
		['shipping', 'greater_than_or_equal_to' => 0],
		['discount', 'less_than_or_equal_to' => 5, 'greater_than_or_equal_to' => 0],
	];
	static \$validates_uniqueness_of = [
		['email', 'message' => 'Sorry that email is taken'],
	];
	static \$validates_format_of = [
		['email', 'with' =>
		'/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/'],
		['password', 'with' =>
			'/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/', 'message' => 'is too weak'],
		];
	*/

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
