<?
require_once(dirname(__FILE__).'/inc.php');



yellow(ascii::marcel().'
----------------
| CREATE USER! |
----------------
');



# GET EMAIL
$email = gets("Enter new user's email:");
if (User::find_by_email($email))
	return fail("Sorry, a user already has this email address.");



# GET USERNAME
$username = gets('Enter the username for this user:');
if (User::find_by_username($username))
	return fail("Sorry, a user already has this username.");



# ESTABLISH User_Type ID (admin, user, etc...)
$user_types = User_Type::all();
yellow("Available User_Types\n");
foreach ($user_types as $ut)
	echo " {$ut->id}. {$ut->name} ({$ut->slug})\n";
$default_user_type_id = User_Type::default_id();
$default_user_type    = User_Type::$default_user;
$user_type_id = gets("Enter new user's User_Type ID: (default is {$default_user_type_id} \"{$default_user_type}\")");



# POPULATE NON-UNIQUE FIELDS
$password = prompt_silent("Enter password:");
$user_data = [
	'email'        => $email,
	'username'     => $username,
	'active'       => 1,
	'verified'     => 1,
	'user_type_id' => (int) isset($user_type_id{0}) ? $user_type_id : $default_user_type_id,
	'password'     => $password,
	'first'        => gets("Enter new user's first name:"),
	'last'         => gets("Enter new user's last name:"),
];



# ACTUALLY CREATE USER
$u = User::create($user_data);



# ENSURE CREATION SUCCESS
if ($u) {
	ok("User:{$u->id} created.\nemail: {$u->email}\nusername: {$u->username}\nuser_type: {$u->user_type->slug}");
	yellow("Note: User assumed `active` = 1 and `verified` = 1.\n");
}

if (count($u->errors)) {
	foreach ($u->errors as $field => $errors)
		fail("{$field}: ". util::list_english($errors));
}
