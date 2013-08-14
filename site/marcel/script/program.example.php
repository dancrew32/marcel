<?
require_once dirname(__FILE__).'/inc.php';
$p = new program;
$p->option([
	'short' => 'd',
	'long'  => 'delete',
	'help'  => 'Delete that thing',
	//'value' => true, # true if must have value
	//'required' => true,
]);
$p->option([
	'short' => 'e',
	'long'  => 'edit',
	'help'  => 'Edit that thing',
	//'value' => true, # true if must have value
	//'required' => true,
]);


if ($p->get('d')) {
	green("Deleted\n");
}

if ($p->get('e')) {
	green("Edited\n");
}

echo $p->help();
