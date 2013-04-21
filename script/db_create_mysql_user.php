<?
require_once(dirname(__FILE__).'/inc.php');

# Auth mysql SuperUser
$my_sql_su = gets('MySQL SuperUser username:'); 
$my_sql_su_pass = prompt_silent("Password:");
$no_history_space = ' ';
$cmd_pre = "{$no_history_space}mysql -u{$my_sql_su} -p{$my_sql_su_pass} -e ";


# Create mysql user for app
$user = gets("New MySQL Username:");
$password = prompt_silent("Password:");

green("Creating new mysql user `{$user}`...\n");
$cmd = "{$cmd_pre} 'CREATE USER \"{$user}\"@\"". DB_HOST ."\" IDENTIFIED BY \"{$password}\"';";
system($cmd);
green("Done.\n");

$grants = [
	'create' => "GRANT CREATE ON ". DB_NAME .".* TO '{$user}'@'". DB_HOST ."'",
	'drop'   => "GRANT DROP ON ". DB_NAME .".* TO '{$user}'@'". DB_HOST ."'",
	'delete' => "GRANT DELETE ON ". DB_NAME .".* TO '{$user}'@'". DB_HOST ."'",
	'insert' => "GRANT INSERT ON ". DB_NAME .".* TO '{$user}'@'". DB_HOST ."'",
	'select' => "GRANT SELECT ON ". DB_NAME .".* TO '{$user}'@'". DB_HOST ."'",
	'update' => "GRANT UPDATE ON ". DB_NAME .".* TO '{$user}'@'". DB_HOST ."'",
];

$to_execute = [];
foreach ($grants as $k => $grant) {
	$ok = strtolower(gets("Grant {$user} {$k} permission? [Y/n]"));
	if ($ok == 'n') continue;
	$to_execute[] = $grant;
}

foreach ($to_execute as $ex) {
	system("{$cmd_pre} '{$ex}';");	
}
green("Permissions granted for {$user}\n");

yellow("Refreshing privileges...\n");
system("{$cmd_pre} 'FLUSH PRIVILEGES';");
green("Done.\n");
