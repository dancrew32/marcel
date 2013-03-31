<?
class app {

	static $title = APP_NAME;

	# routing
	static $path;
	static $domain;
	static $routes = [];
	static $cols = 'no-sidebars';

	# assets
	const JQUERY_VERSION = '1.9.1';
	const JQUERY_UI_VERSION = '1.10.0';
	public static $assets = [
		'js'  => [ ],
		'css' => [
			//'http://fonts.googleapis.com/css?family=Open+Sans:300,600',
			'/css/core/screen.css',
		],
		'error' => [],
		'debug' => [],
	];

	static function layout($o, $yield) {
		$title = self::$title;
		$desc = 'desc';
		$lay = take($o, 'l', 'a');
		$cols = self::$cols;
		if ($lay == 'none')
			unset($lay);
		$css = self::css();
		$js = self::js();
		ob_start();
		include LAYOUT_DIR."/{$lay}.php";
		return ob_get_clean();
	}

	static function css() {
		$out = '';	
		foreach (self::$assets['css'] as $c)
			$out .= '<link href="'. $c .'" rel="stylesheet" type="text/css">';
		return $out;
	}

	static function js() {
		$out = '<script src="'.JS_DIR.'/loader.js"></script>';
		$out .= '<script>
			$LAB.script("//ajax.googleapis.com/ajax/libs/jquery/'.self::JQUERY_VERSION.'/jquery.min.js")
			.script("//ajax.googleapis.com/ajax/libs/jqueryui/'.self::JQUERY_UI_VERSION.'/jquery-ui.min.js").wait()';
		foreach (self::$assets['js'] as $j) {
			$delim = strpos($j, '?') ? '&' : '?';
			$j = CACHE_BUST ? $j.$delim.'d='.date('U') : $j;
			$out .= '.script("'. $j .'")';
		}
		$out .= '</script>';
		# Error Logs
		if (DEBUG && count(self::$assets['debug']))
			$out .= '<script>(function(){'. implode(';', self::$assets['debug']) .'}())</script>';
		if (DEBUG && count(self::$assets['error']))
			$out .= '<script>(function(){'. implode(';', self::$assets['error']) .'}())</script>';
		return $out;
	}

	static function asset($path, $type) {
		$remote = strpos($path, '//') !== false; 
		if ($remote)
			return self::$assets[$type][]= $path;
		$local = "{$path}.{$type}";
		$test = $type == 'css' ? CSS_DIR : JS_DIR;
		if (!file_exists(HTML_DIR.'/'.$test.'/'.$local)) return;
		self::$assets[$type][]= $test.'/'.$local;
	}

	static function errorlag($num, $str, $file, $line) {
		self::$assets['error'][] = "console.error({no:'{$num}',str:'{$str}',file:'{$file}',line:'{$line}'})";
		return true;	
	}

	static function run() {
		if (DEBUG && auth::is_admin() && !util::is_ajax()) {
			# JavaScript Console Errors	
			# set_error_handler('app::errorlag');

			# HTML Output Errors
			ini_set('display_errors', 1);
			ini_set('html_errors', 1);
		}

		# Path
		$path_parts = explode('?', take($_SERVER, 'REQUEST_URI'));
		self::$path = reset($path_parts);
		unset($path_parts);

		# Domain
		$domain_parts = explode('.', take($_SERVER, 'SERVER_NAME'));
		array_pop($domain_parts);
		self::$domain = end($domain_parts);
		unset($domain_parts);

		# Route
		$found = false;
		foreach (self::$routes as $regex => $o) {
			$regex = str_replace('/', '\/', $regex);
			$found = preg_match("/^{$regex}\/?$/i", self::$path, $matches);
			if (!$found) continue;

			# Session and User
			self::session_begin();
			User::init();

			# Main render
			$req = strtolower(take($_SERVER, 'REQUEST_METHOD', 'get'));
			$is_ajax = util::is_ajax();
			$out = render($o, [
				'p' => $matches,
				'm' => $req,
			]);
			if ($is_ajax) {
				echo $out;
			} else {
				Log::hit($matches[0]);
				echo self::layout($o, $out);
			}
			break;
		}


		if (!$found && !CLI) 
			echo r('common', 'not_found');		
	}

	static function session_begin() {
		session_set_save_handler(
			'Session::open',
			'Session::close',
			'Session::read',
			'Session::write',
			'Session::destroy',
			'Session::gc'
		);
		session_name(SESSION_NAME);
		session_start();
		register_shutdown_function('session_write_close');	
	}

	static function redir($url='/') {
		header("Location: {$url}");
		exit;
	}

	static function title($title) {
		self::$title = h($title) .' - '. APP_NAME;
	}

}
