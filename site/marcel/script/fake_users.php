<?
require_once(dirname(__FILE__).'/inc.php');

$user_type = User_Type::find_by_slug('user');
times(250, function() {
	$u = new User;
	$u->first    = fake::firstName();
	$u->last     = fake::lastName();
	$u->email    = fake::safeEmail();
	$u->username = fake::userName();
	$u->user_type_id = $user_type->id;
	$u->password = 'testing';
	$u->save();
});
