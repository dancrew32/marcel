<?
require_once(dirname(__FILE__).'/inc.php');

$ok = true;
$controller = strtolower(gets("Enter View's Controller:"));
$method = strtolower(gets("Enter View's Method:"));

$boilerplate ='<div class="row">
	<div class="span4">

	</div>
	<div class="span4">

	</div>
	<div class="span4">

	</div>
</div>
';

$script_name = "{$controller}.{$method}.php";
$full_script_path = config::$setting['view_dir']."/{$script_name}";

$exists = is_file($full_script_path);
if ($exists)
	return red("View exists.\n");

$ok = file_put_contents($full_script_path, $boilerplate);
if ($ok)
	green("Successfully created view: {$script_name}\n");
else
	red("WRITE FAIL\n");
