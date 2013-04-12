<?

class image_manip {
	const MAX_FILE_SIZE = 10485760;
	const MAX_WIDTH = 1500;
	const MAX_HEIGHT = 1500;

	protected $src = "";
	protected $is404 = false;
	protected $docRoot = "";
	protected $lastURLError = false;
	protected $localImage = "";
	protected $localImageMTime = 0;
	protected $url = false;
	protected $myHost = "";
	protected $isURL = false;
	protected $cachefile = '';
	protected $errors = array();
	protected $toDeletes = array();
	protected $cacheDirectory = '';
	protected $startTime = 0;
	protected $lastBenchTime = 0;
	protected $cropTop = false;
	protected $salt = "";
	protected $fileCacheVersion = 1; //Generally if image_manip.php is modifed (upgraded) then the salt changes and all cache files are recreated. This is a backup mechanism to force regen.
	protected $filePrependSecurityBlock = "<?php die('Execution denied!'); //"; //Designed to have three letter mime type, space, question mark and greater than symbol appended. 6 bytes total.
	protected static $curlDataWritten = 0;
	protected static $curlFH = false;
	public static function start(){
		$tim = new image_manip();
		if ($tim->tryBrowserCache()){
			exit(0);
		}
		if (FILE_CACHE_ENABLED && $tim->tryServerCache()){
			exit(0);
		}
		$tim->run();
		exit(0);
	}
	public function __construct(){
		global $ALLOWED_SITES;
		$this->startTime = microtime(true);
		date_default_timezone_set('UTC');
		$this->calcDocRoot();
		//On windows systems I'm assuming fileinode returns an empty string or a number that doesn't change. Check this.
		$this->salt = @filemtime(__FILE__) . '-' . @fileinode(__FILE__);
		if (FILE_CACHE_DIRECTORY){
			if (!is_dir(FILE_CACHE_DIRECTORY)){
				@mkdir(FILE_CACHE_DIRECTORY);
				if (!is_dir(FILE_CACHE_DIRECTORY)){
					$this->error("Could not create the file cache directory.");
					return false;
				}
			}
			$this->cacheDirectory = FILE_CACHE_DIRECTORY;
			if (!touch($this->cacheDirectory . '/index.html')) {
				$this->error("Could not create the index.html file - to fix this create an empty file named index.html file in the cache directory.");
			}
		} else {
			$this->cacheDirectory = sys_get_temp_dir();
		}
		//Clean the cache before we do anything because we don't want the first visitor after FILE_CACHE_TIME_BETWEEN_CLEANS expires to get a stale image. 
		$this->cleanCache();
		
		$this->myHost = preg_replace('/^www\./i', '', $_SERVER['HTTP_HOST']);
		$this->src = $this->param('src');
		//$this->url = parse_url($this->src);
		//$this->src = preg_replace('/https?:\/\/(?:www\.)?' . $this->myHost . '/img', '', $this->src);
		
		if (strlen($this->src) <= 3){
			$this->error("No image specified");
			return false;
		}
		if (preg_match('/^https?:\/\/[^\/]+/i', $this->src)){
			$this->isURL = true;
		}
		if ($this->isURL && (!ALLOW_EXTERNAL)){
			$this->error("You are not allowed to fetch images from an external website.");
			return false;
		}
		if ($this->isURL){
			$allowed = false;
			foreach(image::$ALLOWED_SITES as $site){
				if ((strtolower(substr($this->url['host'],-strlen($site)-1)) === strtolower(".$site")) || (strtolower($this->url['host'])===strtolower($site)))
					$allowed = true;
			}
			if (!$allowed) return;
		}

		// sig match
		$get_sans_sig = $_GET;
		unset($get_sans_sig['sig']);
		$keygen = image::keygen($get_sans_sig);
		if ($keygen != $this->param('sig'))
			return false;

		$cachePrefix = ($this->isURL ? '_ext_' : '_int_');
		if ($this->isURL){
			$arr = explode('&', $_SERVER ['QUERY_STRING']);
			asort($arr);
			$this->cachefile = $this->cacheDirectory . '/' . FILE_CACHE_PREFIX . $cachePrefix . md5($this->salt . implode('', $arr) . $this->fileCacheVersion) . FILE_CACHE_SUFFIX;
		} else {
			$this->localImage = $this->getLocalImagePath($this->src);
			if (!$this->localImage){
				$this->set404();
				return false;
			}
			$this->localImageMTime = @filemtime($this->localImage);
			//We include the mtime of the local file in case in changes on disk.
			$this->cachefile = $this->cacheDirectory . '/' . FILE_CACHE_PREFIX . $cachePrefix . md5($this->salt . $this->localImageMTime . $_SERVER ['QUERY_STRING'] . $this->fileCacheVersion) . FILE_CACHE_SUFFIX;
		}

		return true;
	}
	public function __destruct(){
		foreach($this->toDeletes as $del){
			@unlink($del);
		}
	}
	public function run(){
		if ($this->isURL){
			if (!ALLOW_EXTERNAL){
				$this->error("You are not allowed to fetch images from an external website.");
				return false;
			}
			$this->serveExternalImage();
		} else {
			$this->serveInternalImage();
		}
		return true;
	}
	protected function tryBrowserCache(){
		if (BROWSER_CACHE_DISABLE) return false;
		if (!empty($_SERVER['HTTP_IF_MODIFIED_SINCE']) ){
			$mtime = false;
			//We've already checked if the real file exists in the constructor
			if (!is_file($this->cachefile)){
				//If we don't have something cached, regenerate the cached image.
				return false;
			}
			if ($this->localImageMTime){
				$mtime = $this->localImageMTime;
			} else if (is_file($this->cachefile)){ //If it's not a local request then use the mtime of the cached file to determine the 304
				$mtime = @filemtime($this->cachefile);
			}
			if (!$mtime){ return false; }

			$iftime = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
			if ($iftime < 1){
				return false;
			}
			if ($iftime < $mtime){ //Real file or cache file has been modified since last request, so force refetch.
				return false;
			} else { //Otherwise serve a 304
				header ($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
				return true;
			}
		}
		return false;
	}
	protected function tryServerCache(){
		if (file_exists($this->cachefile)){
			if ($this->isURL){
				if (filesize($this->cachefile) < 1){
					//Fetching error occured previously
					if (time() - @filemtime($this->cachefile) > 3600){
						@unlink($this->cachefile);
						return false; //to indicate we didn't serve from cache and app should try and load
					} else {
						$this->set404();
						$this->error("An error occured fetching image.");
						return false; 
					}
				}
			} else {
			}
			if ($this->serveCacheFile()){
				return true;
			} else {
				//Image serving failed. We can't retry at this point, but lets remove it from cache so the next request recreates it
				@unlink($this->cachefile);
				return true;
			}
		}
	}
	protected function error($err){
		$this->errors[] = $err;
		return false;

	}
	protected function haveErrors(){
		if (sizeof($this->errors) > 0){
			return true;
		}
		return false;
	}
	protected function serveInternalImage(){
		if (!$this->localImage){
			$this->sanityFail("localImage not set after verifying it earlier in the code.");
			return false;
		}
		$fileSize = filesize($this->localImage);
		if ($fileSize > self::MAX_FILE_SIZE){
			$this->error("The file you specified is greater than the maximum allowed file size.");
			return false;
		}
		if ($fileSize <= 0){
			$this->error("The file you specified is <= 0 bytes.");
			return false;
		}
		if ($this->processImageAndWriteToCache($this->localImage)){
			$this->serveCacheFile();
			return true;
		} else { 
			return false;
		}
	}
	protected function cleanCache(){
		if (FILE_CACHE_TIME_BETWEEN_CLEANS < 0) {
			return;
		}
		$lastCleanFile = $this->cacheDirectory . '/imgcache_cacheLastCleanTime.touch';
		
		//If this is a new installation we need to create the file
		if (!is_file($lastCleanFile)){
			if (!touch($lastCleanFile)) {
				$this->error("Could not create cache clean timestamp file.");
			}
			return;
		}
		if (@filemtime($lastCleanFile) < (time() - FILE_CACHE_TIME_BETWEEN_CLEANS) ){ //Cache was last cleaned more than 1 day ago
			// Very slight race condition here, but worst case we'll have 2 or 3 servers cleaning the cache simultaneously once a day.
			if (!touch($lastCleanFile)) {
				$this->error("Could not create cache clean timestamp file.");
			}
			$files = glob($this->cacheDirectory . '/*' . FILE_CACHE_SUFFIX);
			if ($files) {
				$timeAgo = time() - FILE_CACHE_MAX_FILE_AGE;
				foreach($files as $file){
					if (@filemtime($file) < $timeAgo){
						@unlink($file);
					}
				}
			}
			return true;
		} else {
		}
		return false;
	}
	protected function processImageAndWriteToCache($localImage){
		$sData = getimagesize($localImage);
		$origType = $sData[2];
		$mimeType = $sData['mime'];

		if (!preg_match('/^image\/(?:gif|jpg|jpeg|png)$/i', $mimeType)){
			return $this->error("The image being resized is not a valid gif, jpg or png.");
		}

		if (!function_exists ('imagecreatetruecolor')) {
		    return $this->error('GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library');
		}

		if (function_exists ('imagefilter') && defined ('IMG_FILTER_NEGATE')) {
			$imageFilters = array (
				1 => array (IMG_FILTER_NEGATE, 0),
				2 => array (IMG_FILTER_GRAYSCALE, 0),
				3 => array (IMG_FILTER_BRIGHTNESS, 1),
				4 => array (IMG_FILTER_CONTRAST, 1),
				5 => array (IMG_FILTER_COLORIZE, 4),
				6 => array (IMG_FILTER_EDGEDETECT, 0),
				7 => array (IMG_FILTER_EMBOSS, 0),
				8 => array (IMG_FILTER_GAUSSIAN_BLUR, 0),
				9 => array (IMG_FILTER_SELECTIVE_BLUR, 0),
				10 => array (IMG_FILTER_MEAN_REMOVAL, 0),
				11 => array (IMG_FILTER_SMOOTH, 0),
			);
		}

		// get standard input properties		
		$new_width =  (int) abs ($this->param('w', 0));
		$new_height = (int) abs ($this->param('h', 0));
		$zoom_crop = (int) $this->param('zc', 1);
		$quality = (int) abs ($this->param('q', 90));
		$align = $this->cropTop ? 't' : $this->param('a', 'c');
		$filters = $this->param('f', '');
		$sharpen = (bool) $this->param('s', 0);
		$canvas_color = $this->param('cc', 'ffffff');
		$canvas_trans = (bool) $this->param('ct', '1');

		// set default width and height if neither are set already
		if ($new_width == 0 && $new_height == 0) {
		    $new_width = 100;
		    $new_height = 100;
		}

		// ensure size limits can not be abused
		$new_width = min ($new_width, self::MAX_WIDTH);
		$new_height = min ($new_height, self::MAX_HEIGHT);

		// set memory limit to be able to have enough space to resize larger images
		$this->setMemoryLimit();

		// open the existing image
		$image = $this->openImage ($mimeType, $localImage);
		if ($image === false) {
			return $this->error('Unable to open image.');
		}

		// Get original width and height
		$width = imagesx ($image);
		$height = imagesy ($image);
		$origin_x = 0;
		$origin_y = 0;

		// generate new w/h if not provided
		if ($new_width && !$new_height) {
			$new_height = floor ($height * ($new_width / $width));
		} else if ($new_height && !$new_width) {
			$new_width = floor ($width * ($new_height / $height));
		}

		// scale down and add borders
		if ($zoom_crop == 3) {

			$final_height = $height * ($new_width / $width);

			if ($final_height > $new_height) {
				$new_width = $width * ($new_height / $height);
			} else {
				$new_height = $final_height;
			}

		}

		// create a new true color image
		$canvas = imagecreatetruecolor ($new_width, $new_height);
		imagealphablending ($canvas, false);

		if (strlen($canvas_color) == 3) { //if is 3-char notation, edit string into 6-char notation
			$canvas_color =  str_repeat(substr($canvas_color, 0, 1), 2) . str_repeat(substr($canvas_color, 1, 1), 2) . str_repeat(substr($canvas_color, 2, 1), 2); 
		} else if (strlen($canvas_color) != 6) {
			$canvas_color = 'ffffff'; // on error return default canvas color
 		}

		$canvas_color_R = hexdec (substr ($canvas_color, 0, 2));
		$canvas_color_G = hexdec (substr ($canvas_color, 2, 2));
		$canvas_color_B = hexdec (substr ($canvas_color, 4, 2));

		// Create a new transparent color for image
		$color = imagecolorallocatealpha ($canvas, $canvas_color_R, $canvas_color_G, $canvas_color_B, 127);		


		// Completely fill the background of the new image with allocated color.
		imagefill ($canvas, 0, 0, $color);

		// scale down and add borders
		if ($zoom_crop == 2) {

			$final_height = $height * ($new_width / $width);

			if ($final_height > $new_height) {

				$origin_x = $new_width / 2;
				$new_width = $width * ($new_height / $height);
				$origin_x = round ($origin_x - ($new_width / 2));

			} else {

				$origin_y = $new_height / 2;
				$new_height = $final_height;
				$origin_y = round ($origin_y - ($new_height / 2));

			}

		}

		// Restore transparency blending
		imagesavealpha ($canvas, true);

		if ($zoom_crop > 0) {

			$src_x = $src_y = 0;
			$src_w = $width;
			$src_h = $height;

			$cmp_x = $width / $new_width;
			$cmp_y = $height / $new_height;

			// calculate x or y coordinate and width or height of source
			if ($cmp_x > $cmp_y) {

				$src_w = round ($width / $cmp_x * $cmp_y);
				$src_x = round (($width - ($width / $cmp_x * $cmp_y)) / 2);

			} else if ($cmp_y > $cmp_x) {

				$src_h = round ($height / $cmp_y * $cmp_x);
				$src_y = round (($height - ($height / $cmp_y * $cmp_x)) / 2);

			}

			// positional cropping!
			if ($align) {
				if (strpos ($align, 't') !== false) {
					$src_y = 0;
				}
				if (strpos ($align, 'b') !== false) {
					$src_y = $height - $src_h;
				}
				if (strpos ($align, 'l') !== false) {
					$src_x = 0;
				}
				if (strpos ($align, 'r') !== false) {
					$src_x = $width - $src_w;
				}
			}

			imagecopyresampled ($canvas, $image, $origin_x, $origin_y, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h);

		} else {

			// copy and resize part of an image with resampling
			imagecopyresampled ($canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		}

		if ($filters != '' && function_exists ('imagefilter') && defined ('IMG_FILTER_NEGATE')) {
			// apply filters to image
			$filterList = explode ('|', $filters);
			foreach ($filterList as $fl) {

				$filterSettings = explode (',', $fl);
				if (isset ($imageFilters[$filterSettings[0]])) {

					for ($i = 0; $i < 4; $i ++) {
						if (!isset ($filterSettings[$i])) {
							$filterSettings[$i] = null;
						} else {
							$filterSettings[$i] = (int) $filterSettings[$i];
						}
					}

					switch ($imageFilters[$filterSettings[0]][1]) {

						case 1:

							imagefilter ($canvas, $imageFilters[$filterSettings[0]][0], $filterSettings[1]);
							break;

						case 2:

							imagefilter ($canvas, $imageFilters[$filterSettings[0]][0], $filterSettings[1], $filterSettings[2]);
							break;

						case 3:

							imagefilter ($canvas, $imageFilters[$filterSettings[0]][0], $filterSettings[1], $filterSettings[2], $filterSettings[3]);
							break;

						case 4:

							imagefilter ($canvas, $imageFilters[$filterSettings[0]][0], $filterSettings[1], $filterSettings[2], $filterSettings[3], $filterSettings[4]);
							break;

						default:

							imagefilter ($canvas, $imageFilters[$filterSettings[0]][0]);
							break;

					}
				}
			}
		}

		// sharpen image
		if ($sharpen && function_exists ('imageconvolution')) {

			$sharpenMatrix = array (
					array (-1,-1,-1),
					array (-1,16,-1),
					array (-1,-1,-1),
					);

			$divisor = 8;
			$offset = 0;

			imageconvolution ($canvas, $sharpenMatrix, $divisor, $offset);

		}
		//Straight from Wordpress core code. Reduces filesize by up to 70% for PNG's
		if ( (IMAGETYPE_PNG == $origType || IMAGETYPE_GIF == $origType) && function_exists('imageistruecolor') && !imageistruecolor( $image ) && imagecolortransparent( $image ) > 0 ){
			imagetruecolortopalette( $canvas, false, imagecolorstotal( $image ) );
		}

		$imgType = "";
		$tempfile = tempnam($this->cacheDirectory, 'imgcache_tmpimg_');
		if (preg_match('/^image\/(?:jpg|jpeg)$/i', $mimeType)){ 
			$imgType = 'jpg';
			imagejpeg($canvas, $tempfile, $quality); 
		} else if (preg_match('/^image\/png$/i', $mimeType)){ 
			$imgType = 'png';
			imagepng($canvas, $tempfile, floor($quality * 0.09));
		} else if (preg_match('/^image\/gif$/i', $mimeType)){
			$imgType = 'gif';
			imagegif ($canvas, $tempfile);
		} else {
			return $this->sanityFail("Could not match mime type after verifying it previously.");
		}

		if ($imgType == 'png' && OPTIPNG_ENABLED && OPTIPNG_PATH && @is_file(OPTIPNG_PATH)){
			$exec = OPTIPNG_PATH;
			$presize = filesize($tempfile);
			$out = `$exec -o1 $tempfile`; //you can use up to -o7 but it really slows things down
			clearstatcache();
			$aftersize = filesize($tempfile);
			$sizeDrop = $presize - $aftersize;
			if ($sizeDrop > 0){
			} else if ($sizeDrop < 0){
			} else {
			}
		}

		$tempfile4 = tempnam($this->cacheDirectory, 'imgcache_tmpimg_');
		$context = stream_context_create ();
		$fp = fopen($tempfile,'r',0,$context);
		file_put_contents($tempfile4, $this->filePrependSecurityBlock . $imgType . ' ?' . '>'); //6 extra bytes, first 3 being image type 
		file_put_contents($tempfile4, $fp, FILE_APPEND);
		fclose($fp);
		@unlink($tempfile);
		$lockFile = $this->cachefile . '.lock';
		$fh = fopen($lockFile, 'w');
		if (!$fh){
			return $this->error("Could not open the lockfile for writing an image.");
		}
		if (flock($fh, LOCK_EX)){
			@unlink($this->cachefile); //rename generally overwrites, but doing this in case of platform specific quirks. File might not exist yet.
			rename($tempfile4, $this->cachefile);
			flock($fh, LOCK_UN);
			fclose($fh);
			@unlink($lockFile);
		} else {
			fclose($fh);
			@unlink($lockFile);
			@unlink($tempfile4);
			return $this->error("Could not get a lock for writing.");
		}
		imagedestroy($canvas);
		imagedestroy($image);
		return true;
	}
	protected function calcDocRoot(){
		$docRoot = @$_SERVER['DOCUMENT_ROOT'];
		if (defined('LOCAL_FILE_BASE_DIRECTORY')) {
			$docRoot = LOCAL_FILE_BASE_DIRECTORY;   
		}
		if (!isset($docRoot)){ 
			if (isset($_SERVER['SCRIPT_FILENAME'])){
				$docRoot = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF'])));
			} 
		}
		if (!isset($docRoot)){ 
			if (isset($_SERVER['PATH_TRANSLATED'])){
				$docRoot = str_replace( '\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0-strlen($_SERVER['PHP_SELF'])));
			} 
		}
		if ($docRoot && $_SERVER['DOCUMENT_ROOT'] != '/'){ $docRoot = preg_replace('/\/$/', '', $docRoot); }
		$this->docRoot = $docRoot;

	}
	protected function getLocalImagePath($src){
		$src = ltrim($src, '/'); //strip off the leading '/'
		if (!$this->docRoot){
			//We don't support serving images outside the current dir if we don't have a doc root for security reasons.
			$file = preg_replace('/^.*?([^\/\\\\]+)$/', '$1', $src); //strip off any path info and just leave the filename.
			if (is_file($file)){
				return $this->realpath($file);
			}
			return $this->error("Could not find your website document root and the file specified doesn't exist in directory. We don't support serving files outside directory without a document root for security reasons.");
		} //Do not go past this point without docRoot set

		//Try src under docRoot
		if (file_exists ($this->docRoot . '/' . $src)) {
			$real = $this->realpath($this->docRoot . '/' . $src);
			if (stripos($real, $this->docRoot) === 0){
				return $real;
			} else {
				//allow search to continue
			}
		}
		//Check absolute paths and then verify the real path is under doc root
		$absolute = $this->realpath('/' . $src);
		if ($absolute && file_exists($absolute)){ //realpath does file_exists check, so can probably skip the exists check here
			if (!$this->docRoot){ $this->sanityFail("docRoot not set when checking absolute path."); }
			if (stripos($absolute, $this->docRoot) === 0){
				return $absolute;
			} else {
				//and continue search
			}
		}
		
		$base = $this->docRoot;
		
		// account for Windows directory structure
		if (strstr($_SERVER['SCRIPT_FILENAME'],':')) {
			$sub_directories = explode('\\', str_replace($this->docRoot, '', $_SERVER['SCRIPT_FILENAME']));
		} else {
			$sub_directories = explode('/', str_replace($this->docRoot, '', $_SERVER['SCRIPT_FILENAME']));
		}

		foreach ($sub_directories as $sub){
			$base .= $sub . '/';
			if (file_exists($base . $src)){
				$real = $this->realpath($base . $src);
				if (stripos($real, $this->realpath($this->docRoot)) === 0){
					return $real;
				} else {
					//And continue search
				}
			}
		}
		return false;
	}
	protected function realpath($path){
		//try to remove any relative paths
		$remove_relatives = '/\w+\/\.\.\//';
		while(preg_match($remove_relatives,$path)){
		    $path = preg_replace($remove_relatives, '', $path);
		}
		//if any remain use PHP realpath to strip them out, otherwise return $path
		//if using realpath, any symlinks will also be resolved
		return preg_match('#^\.\./|/\.\./#', $path) ? realpath($path) : $path;
	}
	protected function toDelete($name){
		$this->toDeletes[] = $name;
	}
	protected function serveExternalImage(){
		if (!preg_match('/^https?:\/\/[a-zA-Z0-9\-\.]+/i', $this->src)){
			$this->error("Invalid URL supplied.");
			return false;
		}
		$tempfile = tempnam($this->cacheDirectory, 'imgcache');
		$this->toDelete($tempfile);
		#fetch file here
		if (!$this->getURL($this->src, $tempfile)){
			@unlink($this->cachefile);
			touch($this->cachefile);
			$this->error("Error reading the URL you specified from remote host." . $this->lastURLError);
			return false;
		}

		$mimeType = $this->getMimeType($tempfile);
		if (!preg_match("/^image\/(?:jpg|jpeg|gif|png)$/i", $mimeType)){
			@unlink($this->cachefile);
			touch($this->cachefile);
			$this->error("The remote file is not a valid image. Mimetype = '" . $mimeType . "'" . $tempfile);
			return false;
		}
		if ($this->processImageAndWriteToCache($tempfile)){
			return $this->serveCacheFile();
		} else {
			return false;
		}
	}
	public static function curlWrite($h, $d){
		fwrite(self::$curlFH, $d);
		self::$curlDataWritten += strlen($d);
		if (self::$curlDataWritten > self::MAX_FILE_SIZE){
			return 0;
		} else {
			return strlen($d);
		}
	}
	protected function serveCacheFile(){
		if (!is_file($this->cachefile)){
			$this->error("serveCacheFile called but we couldn't find the cached file.");
			return false;
		}
		$fp = fopen($this->cachefile, 'rb');
		if (!$fp){ return $this->error("Could not open cachefile."); }
		fseek($fp, strlen($this->filePrependSecurityBlock), SEEK_SET);
		$imgType = fread($fp, 3);
		fseek($fp, 3, SEEK_CUR);
		if (ftell($fp) != strlen($this->filePrependSecurityBlock) + 6){
			@unlink($this->cachefile);
			return $this->error("The cached image file seems to be corrupt.");
		}
		$imageDataSize = filesize($this->cachefile) - (strlen($this->filePrependSecurityBlock) + 6);
		$this->sendImageHeaders($imgType, $imageDataSize);
		$bytesSent = @fpassthru($fp);
		fclose($fp);
		if ($bytesSent > 0){
			return true;
		}
		$content = file_get_contents ($this->cachefile);
		if ($content != FALSE) {
			$content = substr($content, strlen($this->filePrependSecurityBlock) + 6);
			echo $content;
			return true;
		} else {
			$this->error("Cache file could not be loaded.");
			return false;
		}
	}
	protected function sendImageHeaders($mimeType, $dataSize){
		if (!preg_match('/^image\//i', $mimeType)){
			$mimeType = 'image/' . $mimeType;
		}
		if (strtolower($mimeType) == 'image/jpg'){
			$mimeType = 'image/jpeg';
		}
		$gmdate_expires = gmdate ('D, d M Y H:i:s', strtotime ('now +10 days')) . ' GMT';
		$gmdate_modified = gmdate ('D, d M Y H:i:s') . ' GMT';
		// send content headers then display image
		header ('Content-Type: ' . $mimeType);
		header ('Accept-Ranges: none'); //Changed this because we don't accept range requests
		header ('Last-Modified: ' . $gmdate_modified);
		header ('Content-Length: ' . $dataSize);
		if (BROWSER_CACHE_DISABLE){
			header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
			header("Pragma: no-cache");
			header('Expires: ' . gmdate ('D, d M Y H:i:s', time()));
		} else {
			header('Cache-Control: max-age=' . BROWSER_CACHE_MAX_AGE . ', must-revalidate');
			header('Expires: ' . $gmdate_expires);
		}
		return true;
	}
	protected function param($property, $default = ''){
		if (isset ($_GET[$property])) {
			return $_GET[$property];
		} else {
			return $default;
		}
	}
	protected function openImage($mimeType, $src){
		switch ($mimeType) {
			case 'image/jpeg':
				$image = imagecreatefromjpeg ($src);
				break;

			case 'image/png':
				$image = imagecreatefrompng ($src);
				imagealphablending( $image, true );
				imagesavealpha( $image, true );
				break;

			case 'image/gif':
				$image = imagecreatefromgif ($src);
				break;
			
			default:
				$this->error("Unrecognised mimeType");
		}

		return $image;
	}
	protected function getIP(){
		$rem = @$_SERVER["REMOTE_ADDR"];
		$ff = @$_SERVER["HTTP_X_FORWARDED_FOR"];
		$ci = @$_SERVER["HTTP_CLIENT_IP"];
		if (preg_match('/^(?:192\.168|172\.16|10\.|127\.)/', $rem)){ 
			if ($ff){ return $ff; }
			if ($ci){ return $ci; }
			return $rem;
		} else {
			if ($rem){ return $rem; }
			if ($ff){ return $ff; }
			if ($ci){ return $ci; }
			return "UNKNOWN";
		}
	}
	protected function sanityFail($msg){
		return $this->error("There is a problem in the code. Message: Please report this error at <a href='http://code.google.com/p/timthumb/issues/list'>timthumb's bug tracking page</a>: $msg");
	}
	protected function getMimeType($file){
		$info = getimagesize($file);
		if (is_array($info) && $info['mime']){
			return $info['mime'];
		}
		return '';
	}
	protected function setMemoryLimit(){
		$inimem = ini_get('memory_limit');
		$inibytes = image_manip::returnBytes($inimem);
		$ourbytes = image_manip::returnBytes('30M');
		if ($inibytes < $ourbytes){
			ini_set ('memory_limit', '30M');
		} else {
		}
	}
	protected static function returnBytes($size_str){
		switch (substr ($size_str, -1))
		{
			case 'M': case 'm': return (int)$size_str * 1048576;
			case 'K': case 'k': return (int)$size_str * 1024;
			case 'G': case 'g': return (int)$size_str * 1073741824;
			default: return $size_str;
		}
	}
	
	protected function getURL($url, $tempfile){
		$this->lastURLError = false;
		$url = preg_replace('/ /', '%20', $url);
		if (function_exists('curl_init')){
			self::$curlFH = fopen($tempfile, 'w');
			if (!self::$curlFH){
				$this->error("Could not open $tempfile for writing.");
				return false;
			}
			self::$curlDataWritten = 0;
			$curl = curl_init($url);
			curl_setopt ($curl, CURLOPT_TIMEOUT, 20);
			curl_setopt ($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.122 Safari/534.30");
			curl_setopt ($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt ($curl, CURLOPT_HEADER, 0);
			curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt ($curl, CURLOPT_WRITEFUNCTION, 'image_manip::curlWrite');
			@curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, true);
			@curl_setopt ($curl, CURLOPT_MAXREDIRS, 10);
			
			$curlResult = curl_exec($curl);
			fclose(self::$curlFH);
			$httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if ($httpStatus == 404){
				$this->set404();
			}
			if ($httpStatus == 302){
				$this->error("External Image is Redirecting. Try alternate image url");
				return false;
			}
			if ($curlResult){
				curl_close($curl);
				return true;
			} else {
				$this->lastURLError = curl_error($curl);
				curl_close($curl);
				return false;
			}
		} else {
			$img = @file_get_contents ($url);
			if ($img === false){
				$err = error_get_last();
				if (is_array($err) && $err['message']){
					$this->lastURLError = $err['message'];
				} else {
					$this->lastURLError = $err;
				}
				if (preg_match('/404/', $this->lastURLError)){
					$this->set404();
				}

				return false;
			}
			if (!file_put_contents($tempfile, $img)){
				$this->error("Could not write to $tempfile.");
				return false;
			}
			return true;
		}

	}
	protected function serveImg($file){
		$s = getimagesize($file);
		if (!($s && $s['mime'])){
			return false;
		}
		header ('Content-Type: ' . $s['mime']);
		header ('Content-Length: ' . filesize($file) );
		header ('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		header ("Pragma: no-cache");
		$bytes = @readfile($file);
		if ($bytes > 0){
			return true;
		}
		$content = @file_get_contents ($file);
		if ($content != FALSE){
			echo $content;
			return true;
		}
		return false;

	}
	protected function set404(){
		$this->is404 = true;
	}
	protected function is404(){
		return $this->is404;
	}
}
