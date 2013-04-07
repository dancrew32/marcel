<?
require_once(dirname(__FILE__).'/inc.php');

# Find db dumps
$databases = glob(DUMP_DIR.'/*.sql');
$db_count = count($databases);
if (!$db_count)
	return red("No databases to restore. Exiting.\n");

# Pick db
rsort($databases);
foreach ($databases as $k => $db) {
	$fname = util::explode_pop('/', $db);
	$index = $k + 1;
	$dt = explode('.', $fname);
	$dt = date('F j, Y, g:i:s a', strtotime($dt[1]));
	$latest = $index == 1 ? ' (latest)' : '';
	echo "{$index}. {$fname} ({$dt}){$latest}\n";
}

# Make sure it's the right db to restore
$index_to_restore = gets("Please pick a database to restore by index (1-{$db_count})");
$is_okay_index = is_numeric($index_to_restore) && $index_to_restore > 0 && $index_to_restore < $db_count;
if (!$is_okay_index)
	return red("Invalid db index. Exiting.\n");

# Restore 
$db_to_restore = $databases[$index_to_restore-1];
$db_to_restore_name = util::explode_pop('/', $db_to_restore);
$restore = strtolower(gets("Are you sure you want to restore {$db_to_restore_name}? [N/y]"));
if ($restore != 'y')
	return yellow("Exiting. Database not restored.\n");

green("Restoring ". DB_DB ." to {$db_to_restore_name}.\n");
$cmd = "mysql --user=". DB_USER ." --password=". DB_PASS; 
$cmd .= " --host=". DB_HOST ." ". DB_DB ." < '{$db_to_restore}'";
shell_exec($cmd);
green("Dump Restored: {$db_to_restore_name}\n");
