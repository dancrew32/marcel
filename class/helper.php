<?

# Render alias
function r($c, $m, $p=[]) {
	return util::render([
		'c' => $c,
		'm' => $m,	
	], $p);
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

function take_post($key, $default='') {
	return take($_POST, $key, $default);
}

function take_get($key, $default='') {
	return take($_GET, $key, $default);
}

# Conditional echo
function echoif($condition, $true, $false = '') {
	echo $condition ? $true : $false;
}

# htmlentities shortcut
function h($str) {
	return htmlentities($str);
}

# ifset($a, $thenb, $thenc)
function ifset() {
	foreach (func_get_args() as $arg)
		if (!empty($arg))
			return $arg;
	return null;
}

# Run function X times
function times($limit, $function) {
	$range = range(0, $limit-1);
	if (is_string($function))
		foreach ($range as $i)
			call_user_func($function, $i);
	else 
		foreach ($range as $i)
			$function($i);	
}

function filter($value, $filter) {
	return util::filter($value, $filter);
}


# Print
function pr($data) {
	print_r($data);	
}

# Pretty print
function pp($data) {
	#echo '<pre>';
	var_dump($data);	
	#echo '</pre>';
}

# Pretty die
function pd($data) {
	die(pp($data));
}

# Die (or echo) JSON
function json($data, $exit=true) {
	ob_end_clean(); # ensure no debug data leaks
	header('Content-Type: application/json');	
	if ($exit)
		die(json_encode($data));
	echo json_encode($data);
}

# Forbidden
function _403() {
	app::redir('/403');	
}

# Missing
function _404() {
	app::redir('/404');	
}

# Error
function _500() {
	app::redir('/500');	
}
