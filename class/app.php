<?
class app {

	# layout title
	static $title; # default to app_name
	static $req_type; # e.g. "post"

	# assets
	static $assets = [
		'js'    => [],
		'css'   => [],
		'error' => [],
		'debug' => [],
	];

	static function run() {
		self::$title = config::$setting['app_name'];

		if (DEBUG && !AJAX) {
			# HTML Output Errors
			ini_set('html_errors', 1);
			ini_set('display_errors', 1);

			# http://xdebug.org/docs/display
			ini_set('xdebug.var_display_max_data', 1024);
			ini_set('xdebug.var_display_max_depth', 10);

			# xhprof
			ini_set('xhprof.output_dir', config::$setting['tmp_dir'].'/xhprof'); 
			profile::start();
		}

		# IP Limit
		if (!CLI && isset($GLOBALS['IP_WHITELIST']) && count($GLOBALS['IP_WHITELIST'])) {
			$allowed = in_array(take($_SERVER, 'REMOTE_ADDR'), $GLOBALS['IP_WHITELIST']);
			if (!$allowed) app::redir('http://google.com');
		}

		# Path
		$path_parts = explode('?', take($_SERVER, 'REQUEST_URI'));
		route::$path = reset($path_parts);
		unset($path_parts);

		# HTTP Method
		self::$req_type = strtolower(take($_SERVER, 'REQUEST_METHOD', 'get'));
		define('POST', self::$req_type == 'post');

		# User Agent
		define('USER_AGENT', take($_SERVER, 'HTTP_USER_AGENT'));

		# Route
		$found = false;
		route::init();
		require_once config::$setting['controller_dir'].'/base.php';

		foreach (route::$routes as $regex => $o) {
			$regex = str_replace('/', '\/', $regex);
			$found = preg_match("/^{$regex}\/?$/i", route::$path, $matches);
			if (!$found) continue;

			# Purge Empty Matches
			$matches = array_filter($matches, 'strlen');

			# Router Auth (Phase 1) (optional)
			$global_auth = isset($o['auth']) ? $o['auth'] : false;

			# HTTP Method Route (optional)
			if (isset($o['http'])) {
				if (!isset($o['http'][self::$req_type])) {
					$found = false;	
					break;
				}
				$o = $o['http'][self::$req_type]; # override
			}

			# DB Bypass? If not, init DB & User Data
			if (!isset($o['nodb'])) {
				db::init();
				Session::init();
				User::init();
				User_Permission::init();
			}

			# Router Auth (Phase 2) (optional)
			if ($global_auth || isset($o['auth'])) {
				$allowed = false;
				if (isset($o['auth']))
					$allowed = auth::can(array_merge((
						is_array($global_auth) ? $global_auth : []
					), $o['auth']));

				if (!$allowed) {
					$found = false;	
					break;
				}
			}

			# Route Name 
			route::$route_name = take($o, 'name');

			# Route Section 
			route::$section_name = take($o, 'section');

			# Main render
			$out = util::render($o, $matches);
			echo AJAX ? $out : self::layout($o, $out);
			break;
		}

		if (!$found && !CLI) 
			echo r('status_code', 'not_found');		

		if (DEBUG && !AJAX)
			profile::stop();

	}

	static function layout($o, $yield) {
		$title = self::$title;
		$desc = 'desc';
		$lay = take($o, 'l', 'a');
		if ($lay == 'none')
			unset($lay);
		$body_classes = [take($o, 'c'), take($o, 'm')];
		ob_start();
		include config::$setting['layout_dir']."/{$lay}.php";
		return ob_get_clean();
	}

	static function asset($path, $type) {
		$remote = strpos($path, '//') !== false; 
		if ($remote)
			return self::$assets[$type][]= $path;
		$local = "{$path}.{$type}";
		$test = $type == 'css' ? config::$setting['css_dir'] : config::$setting['js_dir'];
		$path = "{$test}/{$local}";
		if (!is_file(config::$setting['public_dir']."/{$path}")) return;
		self::$assets[$type][]= $path;
	}

	static function redir($url='/') {
		header("Location: {$url}");
		exit;
	}

	static function reload() {
		self::redir(take($_SERVER, 'REQUEST_URI', '/'));
	}

	static function title($title) {
		self::$title = h($title) .' - '. config::$setting['app_name'];
	}

}
