<?
require_once(dirname(__FILE__).'/inc.php');

$config = config::$setting;

yellow("Dumping {$config['db_name']}\n");
$dt   = date('Y-m-d H:i:s');
$file = "{$config['db_name']}{$dt}.sql";
$cmd  = "mysqldump --single-transaction --user={$config['db_user']} --password={$config['db_pass']}";
$cmd .= " --host={$config['db_host']} {$config['db_name']} > '{$config['dump_dir']}/{$file}'";
shell_exec($cmd);
ok("Dump Complete: {$file}");
