<?
require_once(dirname(__FILE__).'/inc.php');

times(250, function() {
	$u = new User;
	$u->first    = fake::firstName();
	$u->last     = fake::lastName();
	$u->email    = fake::safeEmail();
	$u->username = fake::userName();
	$u->role     = 'user';
	$u->password = 'testing';
	$u->save();
});
