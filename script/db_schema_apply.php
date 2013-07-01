<?
require_once(dirname(__FILE__).'/inc.php');

$schemas = glob(SCHEMA_DIR.'/*.sql');
foreach ($schemas as $k => $sch) {
	$file = util::explode_pop('/', $sch);
	echo "{$k}. $file\n";
}

$id = gets("Pick a single schema to apply by id:");
$sch = take($schemas, $id);
$file = util::explode_pop('/', $sch);
echo " Applying {$file}\n";
$sql = file_get_contents($sch);	
$pdo = new PDO("mysql:host=". DB_HOST .";dbname=". DB_NAME, DB_USER, DB_PASS);
$prep = $pdo->prepare($sql);
$prep->execute();
green("Done applying schema.\n");
