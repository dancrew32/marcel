<?
if (!defined('ROOT_DIR'))
	define('ROOT_DIR', realpath(dirname(dirname(__FILE__))));
require_once(ROOT_DIR.'/public/index.php');
if (CLI) {
	ini_set('mysql.connect_timeout', 300);
	ini_set('default_socket_timeout', 300);
	db::init();
}

# CLI helpers 
function red($text) {
	echo clicolor::fg('red', $text);
}
function green($text) {
	echo clicolor::fg('green', $text);
}
function blue($text) {
	echo clicolor::fg('blue', $text);
}
function yellow($text) {
	echo clicolor::fg('yellow', $text);
}
function gets($msg) {
	echo clicolor::fg('yellow', "{$msg}\n");
	return trim(fgets(STDIN));
}
function prompt_silent($prompt = "Enter Password:") {
	$command = "/usr/bin/env bash -c 'echo OK'";
	if (rtrim(shell_exec($command)) !== 'OK') {
	  trigger_error("Can't invoke bash");
	  return;
	}
	$command = "/usr/bin/env bash -c 'read -s -p \""
	  . addslashes(clicolor::fg('yellow', "{$prompt}\n"))
	  . "\" mypassword && echo \$mypassword'";
	$password = rtrim(shell_exec($command));
	echo "\n";
	return $password;
}

/**
 * Progress
 * @param int $total_size - total size of data set
 * @param int $now_size - current position in data set
 * @param int $total_usize (optional) Used in cURL progress bar
 * @param int $now_usize (optional) Used in cURL progress bar
 */
function progress($total_size, $now_size, $total_usize = 0, $now_usize = 0) {
   	static $start_time;
	$size = 30;

	// If there's no total size, we're done. Get outta here.
	if ($total_size === 0) return;

	// if we go over our bound, just ignore it
	if($now_size > $total_size) return;

	if(empty($start_time)) $start_time=time();
	$now = time();

	$percent = (double) ($now_size / $total_size);

	$bar = floor($percent*$size);

	$status_bar="\r[";
	$status_bar.=str_repeat("=", $bar);
	if ($bar < $size) {
		$status_bar .= ">";
		$status_bar .= str_repeat(" ", $size - $bar);
	} else {
		$status_bar .= "=";
	}

	$display = number_format($percent *100, 0);

	$status_bar .= "] $display%  $now_size/$total_size";

	$rate = ($now - $start_time) / ($now_size === 0 ? 1 : $now_size);
	$left = $total_size - $now_size;
	$eta = round($rate * $left, 2);

	$elapsed = $now - $start_time;

	$status_bar.= " remaining: ".number_format($eta)." sec.  elapsed: ".number_format($elapsed)." sec.";

	echo "$status_bar  \r";
	flush();
}

# DELETE LINE with MATCH
function delete_line_with_match($file, $string) {
	$i = 0;
	$temp = array();

	$read = fopen($file, "r") or die("can't open the file");
	while(!feof($read)) {
		$temp[$i] = fgets($read);	
		++$i;
	}
	fclose($read);

	$write = fopen($file, "w") or die("can't open the file");
	foreach($temp as $a)
		if (!strstr($a,$string)) fwrite($write, $a);
	fclose($write);
}

# REPLACE LINE with MATCH
function replace_line_with_match($file, $string, $replacement='') {
	$i = 0;
	$temp = array();

	$read = fopen($file, "r") or die("can't open the file");
	while(!feof($read)) {
		$temp[$i] = fgets($read);	
		++$i;
	}
	fclose($read);

	$write = fopen($file, "w") or die("can't open the file");
	foreach($temp as $a)
		if (!strstr($a,$string)) 
			fwrite($write, $a);
		else
			fwrite($write, $replacement);
	fclose($write);
}
