<?
require_once(dirname(__FILE__).'/inc.php');
$u = new User;
$u->email = gets("Email:");
if (User::find_by_email($u->email))
	return red("User exists\n");

$pass = prompt_silent("Password:");
$u->password = $pass;
$u->active = 1;
$user_type_id = gets('User Type Id:');
if (!isset($user_type_id{0}))
	$user_id = User_Type::default_id();
$u->user_type_id = $user_type_id;
$u->first = gets('First name:');
$u->last = gets('Last name:');
$u->username = gets('Username:');
if ($u->save())
	green("User:{$u->id} created.\n");
