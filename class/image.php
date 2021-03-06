<?
class image {

	const BLANK = '/img/blank.gif';
	static $process_path = false;

	static $ALLOWED_SITES = array (
		'flickr.com',
		'staticflickr.com',
		'picasa.com',
		'ecx.images-amazon.com',
		'img.youtube.com',
		'upload.wikimedia.org',
		'photobucket.com',
		'imgur.com',
		'imageshack.us',
		'tinypic.com',
	);

	static function set_process_path() {
		if (!self::$process_path)
			self::$process_path = route::get("Image Process");
	}

	static function process() {
		require_once config::$setting['vendor_dir'].'/image_manip.php';
		self::config();	
		image_manip::start();
	}

	static function config() {
		# TODO: use registry to drive image_manip.php instead of `define`s

		// Allow image fetching from external websites. 
		// Will check against ALLOWED_SITES if ALLOW_ALL_EXTERNAL_SITES is false
		if (!defined('ALLOW_EXTERNAL'))	
			define('ALLOW_EXTERNAL', TRUE);						

		// Allow fetching from all external sites (DANGEROUS)
		if (!defined('ALLOW_ALL_EXTERNAL_SITES'))
			define('ALLOW_ALL_EXTERNAL_SITES', true);

		// Should we store resized/modified images on disk to speed things up?
		if (!defined('FILE_CACHE_ENABLED')) 
			define('FILE_CACHE_ENABLED', true);

		// How often the cache is cleaned 
		if (!defined('FILE_CACHE_TIME_BETWEEN_CLEANS'))	
			define('FILE_CACHE_TIME_BETWEEN_CLEANS', 86400);

		// How old does a file have to be to be deleted from the cache
		if (!defined('FILE_CACHE_MAX_FILE_AGE'))
			define('FILE_CACHE_MAX_FILE_AGE', 86400);

		// What to put at the end of all files in the cache directory so we can identify them
		if (!defined('FILE_CACHE_SUFFIX'))
			define('FILE_CACHE_SUFFIX', '.imgcache.txt');

		// What to put at the beg of all files in the cache directory so we can identify them
		if (!defined('FILE_CACHE_PREFIX'))
			define('FILE_CACHE_PREFIX', 'imgcache');				

		// Directory where images are cached. 
		// Left blank it will use the system temporary directory (which is better for security)
		if (!defined('FILE_CACHE_DIRECTORY'))
			define('FILE_CACHE_DIRECTORY', config::$setting['imagecache_dir']);	

		//Browser caching
		// Time to cache in the browser
		if (!defined('BROWSER_CACHE_MAX_AGE'))
			define('BROWSER_CACHE_MAX_AGE', 864000);				

		// Use for testing if you want to disable all browser caching
		if (!defined('BROWSER_CACHE_DISABLE'))
			define('BROWSER_CACHE_DISABLE', false);				

		// opti-png crushing (sudo apt-get install optipng)
		if (!defined('OPTIPNG_ENABLED'))
			define('OPTIPNG_ENABLED', false);  
		if (!defined('OPTIPNG_PATH'))
			define('OPTIPNG_PATH', '/usr/bin/optipng');

		self::set_process_path();
	}

	# http://www.binarymoon.co.uk/2012/02/complete-timthumb-parameters-guide/
	static function get(array $o=array(), $html=false, $alt='') {
		self::set_process_path();
		$o = array_merge([
			'src' => '',  // TODO: add default image
			'w'   => null,
			'h'   => null,
			'q'   => '85', 
			'a'   => null, # Crop alignment (chainable) c, t, l, r, b, tl, tr, bl, br
			'zc'  => null, # 0 - size to fit (ugly), 1 - crop resize (default), 2 proportional fit, 3 fill proportional
			# 1 invert, 2 grey, 3,% Brightness, 4,% Contrast, 5,rgba Colorize, 
			# 6 Edges, 7 Emboss, 8 Gaussian, 9 Selective Blur, 10 sketch, 11 Smooth
			# to use multiple: 'f' => 2|1,10 (http://www.binarymoon.co.uk/demo/timthumb-filters/)
			'f'   => null, 
			's'   => null, # sharpen
			'cc'  => null, # canvas colour (e.g. #ffffff)
			'ct'  => null, # canvas transparency (ignores cc)
			'r'   => 0, # revealable? see unveil.js for images that fade in when they show up in the viewport
		], $o);

		if (!isset($o['src']{0}))
			throw new Exception('Must have `src`');
		
		$params = http_build_query($o, '', '&amp;');
		$path = self::$process_path."?{$params}";

		$path .= '&amp;sig='.self::keygen($o);

		if (!$html) return $path;
		$attrs = '';
		# always needs alt
		$alt = h(isset($alt{0}) ? $alt : $o['src']);
		$attrs .= " alt=\"{$alt}\"";
		if ($o['w'])
			$attrs .= " width=\"{$o['w']}\"";
		if ($o['h'])
			$attrs .= " height=\"{$o['h']}\"";
		if ($o['r']) { // reveal mode
			app::asset('class/unveil', 'js');
			$out = "<img class=\"unveilable\" src=\"". self::BLANK ."\" data-src=\"{$path}\"";
		} else {
			$out = "<img src=\"{$path}\"";
		}
		return $out ." {$attrs}>";
	}

	static function keygen($o) {
		return md5(config::$setting['salt'].implode('', $o));
	}

	static function compare($a, $b) {
		# TODO:
		# http://www.intelliot.com/2008/03/sorted-directory-listing-image-resizing-comparison-and-similarity-in-php/
		# http://www.phpied.com/image-diff/
	}
}
