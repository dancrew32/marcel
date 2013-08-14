<?

# CLI helpers 
function red($text) {
	echo clicolor::fg('red', $text);
}
function green($text) {
	echo clicolor::fg('green', $text);
}
function fail($text='FAIL') {
	red("{$text}\n");
}
function ok($text='OK') {
	green("{$text}\n");
}
function blue($text) {
	echo clicolor::fg('blue', $text);
}
function yellow($text) {
	echo clicolor::fg('yellow', $text);
}
function gets($msg, array $options = []) {
	echo clicolor::fg('yellow', "{$msg}\n");
	$out = trim(fgets(STDIN));
	if (in_array('lower', $options))
		$out = strtolower($out); 
	return $out;
}
function can_invoke_bash() {
	$test_string = 'OK';
	$command = "/usr/bin/env bash -c 'echo {$test_string}'";
	return rtrim(shell_exec($command)) === $test_string;
}
function prompt_silent($prompt = "Enter Password:") {
	if (!can_invoke_bash()) 
		return trigger_error("Can't invoke bash");
	$prompt   = addslashes(clicolor::fg('yellow', "{$prompt}\n"));
	$command  = "/usr/bin/env bash -c 'read -s -p \"{$prompt}\" mypassword && echo \$mypassword'";
	$password = rtrim(shell_exec($command));
	echo "\n";
	return $password;
}
function root_plz() {
	if (trim(shell_exec('whoami')) == 'root') return true;
	die("sudo !!");
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
