<?
class config {
	static $setting = [];

	static $required = [
		# CORE DIRECTORIES
		'root_dir',
		'site_dir',

		# APP SETTINGS
		'base_url',
		'admin_email',
		'app_name',
		'salt',
		'session_name',
		
		# DB
		'db_user',
		'db_pass',
		'db_host',
		'db_name',
	];

	static function init(array $settings) {
		$ex = [];
		foreach (self::$required as $req)
			if (!isset($settings[$req]{0}))
				$ex[] = $req;

		if (count($ex))
			throw new Exception("Your config needs: ". implode(', ', $ex));

		# CORE DIRECTORIES
		$settings = array_merge([ 

			# ROOT
			'class_dir'      => "{$settings['root_dir']}/class",
			'model_dir'      => "{$settings['root_dir']}/model",
			'vendor_dir'     => "{$settings['root_dir']}/vendor",
			'tmp_dir'        => "{$settings['root_dir']}/tmp",
			'font_dir'       => "{$settings['root_dir']}/font",

			# SITE
			'db_dir'         => "{$settings['site_dir']}/db",
			'config_dir'     => "{$settings['site_dir']}/config",
			'controller_dir' => "{$settings['site_dir']}/controller",
			'view_dir'       => "{$settings['site_dir']}/view",
			'public_dir'     => "{$settings['site_dir']}/public",
			'script_dir'     => "{$settings['site_dir']}/script",

		], $settings);

		# TEMPORARY DIRECTORIES
		$settings = array_merge([ 
			'imagecache_dir' => "{$settings['tmp_dir']}/imagecache",
		], $settings);

		# VIEW DIRECTORIES
		$settings = array_merge([ 
			'layout_dir' => "{$settings['view_dir']}/layout",
		], $settings);

		# DB DIRECTORIES
		$settings = array_merge([ 
			'schema_dir' => "{$settings['db_dir']}/schema",
			'dump_dir'   => "{$settings['db_dir']}/dump",
		], $settings);

		# PUBLIC DIRECTORIES
		$settings = array_merge([ 
			'image_dir' => "{$settings['public_dir']}/img",
		], $settings);

		# RELATIVE DIRECTORIES
		$settings = array_merge([ 
			'css_dir' => "/css",
			'js_dir'  => "/js",
		], $settings);

		self::$setting = array_merge([
			'timezone'     => 'America/Los_Angeles',
			'ip_whitelist' => [],
		], $settings);


		// CONSTANTS 
		if (!defined('ENV'))
			define('ENV', (getenv('ENV') ? getenv('ENV') : 'DEV'));
		define('DEBUG', ENV == 'DEV');

		define('CACHE_BUST', true);
		define('START_TIME', microtime(true));
		define('CLI', PHP_SAPI == 'cli');
		define('AJAX', (
			isset($_SERVER['HTTP_X_REQUESTED_WITH']{0}) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'));

		require_once self::$setting['class_dir'].'/helper.php';

		# App Autoload
		spl_autoload_register(function($class_name) {
			$file = config::$setting['class_dir']."/{$class_name}.php";
			if (is_file($file))
				require_once $file;
		});
	}

}
