<?
class app {

	static $title = APP_NAME;

	# routing
	static $path;
	static $routes = [];
	static $req_type;

	# assets
	const JQUERY_VERSION = '1.9.1';
	public static $assets = [
		'js'    => ['/js/class/app.js'],
		'css'   => ['/css/core/screen.css'],
		'error' => [],
		'debug' => [],
	];

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
		foreach (self::$assets['css'] as $c)
			$out .= '<link href="'. $c .'" rel="stylesheet" type="text/css">';
		return $out;
	}

	static function js() {
		$cdn = '//ajax.googleapis.com/ajax/libs';
		$out = '<script src="'.JS_DIR.'/loader.js"></script>';
		$out .= '<script>
			$LAB.script("'. $cdn .'/jquery/'.self::JQUERY_VERSION.'/jquery.min.js")
			.script("/js/bootstrap.min.js").wait()';
		foreach (self::$assets['js'] as $j) {
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
		if (!file_exists(PUBLIC_DIR.'/'.$test.'/'.$local)) return;
		self::$assets[$type][]= $test.'/'.$local;
	}

	static function run() {
		if (DEBUG && !util::is_ajax()) {
			# HTML Output Errors
			ini_set('display_errors', 1);
			ini_set('html_errors', 1);
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
				self::db_init();
				self::session_begin();
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

			# Main render
			$out = render($o, ['params' => $matches]);
			echo util::is_ajax() ? $out : self::layout($o, $out);
			break;
		}


		if (!$found && !CLI) 
			echo r('status_code', 'not_found');		
	}


	static function db_init() {
		# Active Record
		require_once VENDOR_DIR.'/activerecord/ActiveRecord.php';
		ActiveRecord\Config::initialize(function($cfg) {
			$cfg->set_model_directory(MODEL_DIR);
			$cfg->set_connections([
				'default' => 'mysql://'. DB_USER .':'. DB_PASS .'@'. DB_HOST .'/'. DB_NAME,
			]);
			$cfg->set_default_connection('default');
		});
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
