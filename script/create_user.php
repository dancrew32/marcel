<?
require_once(dirname(__FILE__).'/inc.php');
$u = new User;
$u->email = gets("Email:");
if (User::find_by_email($u->email))
	return red("User exists\n");

$pass = prompt_silent("Password:");
$u->password = $u->spass($pass);
$u->active = 1;
$u->role = gets('Role:');
$u->first = gets('First name:');
$u->last = gets('Last name:');
$now = db::dtnow();
$u->login_count = 0;
if ($u->save())
	green("User:{$u->id} created.\n");
