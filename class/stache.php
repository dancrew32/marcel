<?
class stache {
	static $loaded = false;
	static $templates = [];
	static function render($view, $vars) {
		require_once VENDOR_DIR."/mustache/src/Mustache/Autoloader.php";
		Mustache_Autoloader::register();
		$view_hash = md5($view);
		$script = '';
		$template = file_get_contents($view);
		if (isset($vars['_template']) || !in_array($view_hash, self::$templates)) {
			$id = preg_replace('/(.*\/)|.mustache$/', '', $view);;
			$id = str_replace('.', '-', $id);
			$script = "<script id=\"{$id}\" type=\"text/mustache\">{$template}</script>\n";
			self::$templates[] = $view_hash;
			if (isset($vars['_template_only']))
				return $script;
		}

		$m = new Mustache_Engine([
			'cache' => TMP_DIR.'/mustachecache'
		]);	
		$out = $m->render($template, $vars);
		return $out.$script;
	}
}
