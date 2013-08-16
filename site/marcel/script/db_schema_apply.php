<?
require_once(dirname(__FILE__).'/inc.php');

$config = config::$setting;

$schemas = glob("{$config['schema_dir']}/*.sql");
foreach ($schemas as $k => $sch) {
	$file = util::explode_pop('/', $sch);
	echo "{$k}. $file\n";
}

$id   = gets("Pick a single schema to apply by id:");
$sch  = take($schemas, $id);
$file = util::explode_pop('/', $sch);
echo " Applying {$file}\n";
$sql  = file_get_contents($sch);	
$pdo  = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']}", $config['db_user'], $config['db_pass']);
$prep = $pdo->prepare($sql);
$prep->execute();
ok("Done applying schema.");
