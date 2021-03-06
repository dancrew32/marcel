<?
require_once(dirname(__FILE__).'/inc.php');

$config = config::$setting;

$name = gets('Enter script name:');
$name = str_replace(' ', '-', $name);
$name = str_replace("/[^a-zA-Z0-9\.]/", '-', $name);
if (!isset($name{0})) {
	red('You must enter a name.');		
	exit;
}

$boilerplate = "<?
require_once(dirname(__FILE__).'/inc.php');

";
$boilerplate .='$ok = true;
$data = gets("Enter Info:");
';

$boilerplate .="
if (\$ok)
	green(\"OK\\n\");
else
	red(\"FAIL\\n\");
";

$use_date = strtolower(gets("Date? [y/N]"));
$date = date('ymd');
if ($use_date == 'y')
	$script_name = "{$name}.{$date}.php";
else
	$script_name = "{$name}.php";

$is_cron = strtolower(gets("Is this a Cron? [y/N]"));
if ($is_cron == 'y')
	$script_name = "cron.{$script_name}";

$full_script_path = "{$config['script_dir']}/{$script_name}";

$exists = is_file($full_script_path);

if ($exists) {
	$overwrite = gets("{$script_name} exists. Overwrite? y/(n)");
	if ($overwrite != 'y') {
		$script_name = $use_date ? "{$name}.{$date}.alt.php" : "{$name}.alt.php";
		$full_script_path = "{$config['script_dir']}/{$script_name}";
		blue("Writing to {$script_name}...\n");
	}
}

$ok = file_put_contents($full_script_path, $boilerplate);
if ($ok)
	green("Successfully added: {$script_name}\n");
else
	red("Unable to save {$script_name}");
