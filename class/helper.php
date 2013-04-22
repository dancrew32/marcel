<?

# Render
function render($o, $p) {
	require_once CONTROLLER_DIR.'/'.$o['c'].'.php';
	$c = "controller_{$o['c']}";
	$obj = new $c($p);
	$obj->$o['m']($p);
	$_view = VIEW_DIR.'/'.$o['c'].'.'.$o['m'].'.php';
	if (!file_exists($_view)) return;
	if ($obj->skip) return $obj->skip = false;
	ob_start();
	extract((array)$obj);
	include $_view;
	return ob_get_clean();
}

# Render alias
function r($c, $m, $p=[]) {
	return render([
		'c' => $c,
		'm' => $m,	
	], $p);
}

# Helper for include
function partial($part) {
	return PARTIAL_DIR."/{$part}.php"; # Must "include" in view
}


# Conditional echo
function echoif($condition, $true, $false = '') {
	echo $condition ? $true : $false;
}

function times($limit, $function) {
	$range = range(0, $limit-1);
	if (is_string($function))
		foreach ($range as $i)
			call_user_func($function, $i);
	else 
		foreach ($range as $i)
			$function($i);	
}

# htmlentities shortcut
function h($str) {
	return htmlentities($str);
}

# Access Attributes
function take($array, $key, $default = '') {
	$o = is_object($array);
	if (!$o && isset($array[$key]))
		return $array[$key];
	if ($o && isset($array->$key))
		return $array->$key;
	return $default;
}

# ifset($a, $thenb, $thenc)
function ifset() {
	foreach (func_get_args() as $arg)
		if (!empty($arg))
			return $arg;
	return null;
}


# Print
function pr($data) {
	print_r($data);	
}

# Pretty print
function pp($data) {
	echo '<pre>';
	pr($data);	
	echo '</pre>';
}

# Pretty die
function pd($data) {
	die(pp($data));
	exit;
}

# console.log() debug
function pj($data) {
	$data = json_encode(pr($data, true));
	app::$assets['debug'][] = "console.log({$data})";
}

# Die (or echo) JSON
function json($data, $exit=true) {
	ob_end_clean(); # ensure no debug data leaks
	header('Content-Type: application/json');	
	if ($exit)
		die(json_encode($data));
	echo json_encode($data);
}

# Missing
function _404() {
	app::redir('/404');	
}

# Error
function _500() {
	app::redir('/500');	
}
