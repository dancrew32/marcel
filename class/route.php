<?
class route {

	const ID = '(?P<id>([0-9]+))';
	const PAGE_FILTER_FORMAT = '(?:/*)(?P<page>[0-9]*)(?:/*)(?P<filter>([a-z]*))(?P<format>\.*[a-z]*)';
	const IGNORED_SLASH = '#\(\?\:/\*\)#';
	const IGNORED_CAPTURE = '#\(.*\)#';

	static $routes = [];
	static $path;         # e.g. "/my/url"
	static $route_name;   # e.g. "User Home"
	static $section_name; # e.g. "User"

	static $get_cache = []; # fast route lookups

	static function crud($prefix, $controller, $name, $section) {
		return [
			"{$prefix}/add"   
				=> [ 'c' => $controller, 'm' => 'add', 'section' => $section, 'name' => "{$name} Add" ],
			"{$prefix}/edit/". self::ID
				=> [ 'c' => $controller, 'm' => 'edit', 'section' => $section, 'name' => "{$name} Edit" ],
			"{$prefix}/delete/". self::ID
				=> [ 'c' => $controller, 'm' => 'delete', 'section' => $section, 'name' => "{$name} Delete"  ],
			"{$prefix}". self::PAGE_FILTER_FORMAT
				=> [ 'c' => $controller, 'm' => 'all', 'section' => $section, 'name' => "{$name} Home" ],
		];
	}

	static function cache_key() {
		return cache::keygen(__CLASS__, __FUNCTION__);
	}

	static function init() {
		$key = self::cache_key();
		self::$routes = json_decode(cache::get($key, $found), true);
		if (!$found) {
			require_once CONFIG_DIR.'/routes.php'; # Routes
			cache::set($key, json_encode(self::$routes), time::ONE_HOUR);
		}
	}

	static function add(array $route) {
		self::$routes += $route;
	}

	static function get($name, array $params=[]) {
		$found = take(self::$get_cache, $name, false);
		if (!$found) {
			$found = util::pluck_key($name, 'name', self::$routes);
			if (!$found) return false;
			self::$get_cache[$name] = $found;
		}
		if (!count($params))
			return preg_replace(self::IGNORED_CAPTURE, '', $found); # ignore captures
		$found = preg_replace(self::IGNORED_SLASH, '/', $found); # restore ignored slashes
		foreach ($params as $k => $v) {
			$found = preg_replace("#\(\?P<($k)>.*\)#", $v, $found, -1, $count);
			if (!$count)
				$query[$k] = $v;
		}
		$found = rtrim($found);
		if (isset($query)) # param misses -> query string (?a=foo&b=bar)
			$found .= '?'. http_build_query($query, '', '&amp;');
		return $found;
	}

	static function in_section($section) {
		return self::$section_name == $section;
	}

	static function in_sections($sections=[]) {
		return in_array(self::$section_name, $sections);
	}

}
