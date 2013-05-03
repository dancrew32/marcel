<?
class app {

	# layout title
	static $title = APP_NAME;

	# routing
	static $routes = [];
	static $path; # e.g. "/my/url"
	static $route_name; # e.g. "User Home"
	static $section_name; # e.g. "user"
	static $req_type; # e.g. "post"

	# assets
	const JQUERY_VERSION = '1.9.1';
	static $assets = [
		'js'    => ['/js/class/app.js', '/js/class/json.js'],
		'css'   => ['/css/core/screen.css'],
		'error' => [],
		'debug' => [],
	];

	static function run() {
		if (DEBUG && !util::is_ajax()) {
			# HTML Output Errors
			ini_set('display_errors', 1);
			ini_set('html_errors', 1);
		}

		# IP Limit
		if (!CLI && isset($GLOBALS['IP_WHITELIST']) && count($GLOBALS['IP_WHITELIST'])) {
			$allowed = in_array(take($_SERVER, 'REMOTE_ADDR'), $GLOBALS['IP_WHITELIST']);
			if (!$allowed) app::redir('http://google.com');
		}

		# Path
		$path_parts = explode('?', take($_SERVER, 'REQUEST_URI'));
		self::$path = reset($path_parts);
		unset($path_parts);

		# HTTP Method
		self::$req_type = strtolower(take($_SERVER, 'REQUEST_METHOD', 'get'));

		# Route
		$found = false;
		foreach (self::$routes as $regex => $o) {
			$regex = str_replace('/', '\/', $regex);
			$found = preg_match("/^{$regex}\/?$/i", self::$path, $matches);
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

			# DB Bypass? If not, init DB and User Sessions
			if (!isset($o['nodb'])) {
				db::init();
				Session::session_begin();
				User::init();
			}

			# Router Auth (Phase 2) (optional)
			if ($global_auth || isset($o['auth'])) {
				$allowed = false;
				if (isset($o['auth']))
					$global_auth = array_merge((
						is_array($global_auth) ? $global_auth : []
					), $o['auth']);
				foreach($global_auth as $ga) {
					if (auth::$ga()) {
						$allowed = true;
						break; 	  
					}
				}
				if (!$allowed) {
					$found = false;	
					break;
				}
			}

			# Route Name 
			if (isset($o['name']{0}))
				self::$route_name = $o['name'];

			# Route Section 
			if (isset($o['section']{0}))
				self::$section_name = $o['section'];

			# Main render
			$out = util::render($o, ['params' => $matches]);
			echo util::is_ajax() ? $out : self::layout($o, $out);
			break;
		}

		if (!$found && !CLI) 
			echo r('status_code', 'not_found');		
	}

	static function layout($o, $yield) {
		$title = self::$title;
		$desc = 'desc';
		$lay = take($o, 'l', 'a');
		if ($lay == 'none')
			unset($lay);
		$css = self::css();
		$js = self::js();
		$body_classes = [take($o, 'c'), take($o, 'm')];
		ob_start();
		include LAYOUT_DIR."/{$lay}.php";
		return ob_get_clean();
	}

	static function css() {
		$out = '';	
		foreach (array_unique(self::$assets['css']) as $c)
			$out .= '<link href="'. $c .'" rel="stylesheet" type="text/css">';
		return $out;
	}

	static function js() {
		$cdn = '//ajax.googleapis.com/ajax/libs';
		$out = '<script src="'.JS_DIR.'/loader.js"></script>';
		$out .= '<script>$LAB.script("'. $cdn .'/jquery/'.self::JQUERY_VERSION.'/jquery.min.js")
			.script("/js/bootstrap.min.js").wait()';
		foreach (array_unique(self::$assets['js']) as $j) {
			$delim = strpos($j, '?') ? '&' : '?';
			$j = CACHE_BUST ? $j.$delim.'d='.date('U') : $j;
			$out .= '.script("'. $j .'")';
		}
		$out .= '</script>';
		return $out;
	}

	static function asset($path, $type) {
		$remote = strpos($path, '//') !== false; 
		if ($remote)
			return self::$assets[$type][]= $path;
		$local = "{$path}.{$type}";
		$test = $type == 'css' ? CSS_DIR : JS_DIR;
		$path = "{$test}/{$local}";
		if (!file_exists(PUBLIC_DIR."/{$path}")) return;
		self::$assets[$type][]= $path;
	}

	static function redir($url='/') {
		header("Location: {$url}");
		exit;
	}

	static function get_path($name) {
		# TODO: instance cache for route path/name key/value
		$found = util::pluck_key($name, 'name', self::$routes);
		if (!$found) return false;
		return preg_replace('/\(.*\)/', '', $found); # ignore captures
	}

	static function in_section($section) {
		return self::$section_name == $section;
	}

	static function title($title) {
		self::$title = h($title) .' - '. APP_NAME;
	}

}
