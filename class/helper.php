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
function r($c, $m, $p=[]) {
	return render([
		'c' => $c,
		'm' => $m,	
	], $p);
}
function partial($part) {
	return PARTIAL_DIR."/{$part}.php"; # Must "include" in view
}


# Print
function pr($data) {
	print_r($data);	
}
function pp($data) {
	echo '<pre>';
	pr($data);	
	echo '</pre>';
}
function pd($data) {
	die(pp($data));
	exit;
}
function pj($data) {
	$data = json_encode(pr($data, true));
	app::$assets['debug'][] = "console.log({$data})";
}
function echoif($condition, $true, $false = '') {
	echo $condition ? $true : $false;
}
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
function ifset() {
	foreach (func_get_args() as $arg)
		if (!empty($arg))
			return $arg;
	return null;
}

# Error
function _404() {
	app::redir('/404');	
}
