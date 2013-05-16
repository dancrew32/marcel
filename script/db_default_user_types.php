<?
require_once(dirname(__FILE__).'/inc.php');

$default_user_types = strtolower(gets("Set default user types? [Y/n]"));
if ($default_user_types) {
	User_Type::create(['name' => 'Admin', 'slug' => 'admin']);
	User_Type::create(['name' => 'Manager', 'slug' => 'manager']);
	User_Type::create(['name' => 'User', 'slug' => 'user']);
}
