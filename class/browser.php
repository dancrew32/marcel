<?
class browser {

	# https://github.com/Element-34/php-webdriver

	const SELENIUM_VERSION = '2.33.0';
	const SELENIUM_PORT = 4444;

	private $instance = false;
	public $session  = false;

	private static $selectors = [
		'id'        => 'id',
		'xpath'     => 'xpath',
		'link'      => 'link text',
		'link_part' => 'partial link text',
		'tag'       => 'tag name',
		'class'     => 'class name',
		'css'       => 'css selector',
	];

	function __construct($browser='firefox') {
		$config = config::$setting;
		require_once "{$config['vendor_dir']}/webdriver/PHPWebDriver/__init__.php";
		$wd_host = "http://{$config['base_url']}:".self::SELENIUM_PORT.'/wd/hub';
		$this->instance = new PHPWebDriver_WebDriver($wd_host);
		$this->session($browser);
		return $this;
	}

	function __destruct() {
		$this->close();
	}

	function session($browser='firefox') {
		$this->session = $this->instance->session($browser);
		return $this;
	}

	function open($url) {
		$this->session->open($url);
		return $this;
	}

	function close() {
		$this->session->close();
		return $this;
	}

	function url_ok($url) {
		similar_text($url, $this->session->url(), $percent);
		return $percent > 50;
	}

	function wait($seconds=3) {
		$this->session->implicitlyWait($seconds);
		return $this;
	}

	function wait_for($selector) {
		$w = new PHPWebDriver_WebDriverWait($this->session);
		$type = self::$selectors['css'];
		$ok = $w->until(function($session) use ($type, $selector) {
			return count($session->elements($type, $selector));
		});
		if (!$ok)
			throw new Exception("Request timed out.");
		return $this;
	}

	function can_do() {
		return $this->session->capabilities();
	}

	function set_size($w, $h) {
		$this->session->window()->postSize(['width' => $w, 'height' => $h]);
		return $this;
	}

	function find($selector) {
		return $this->session->elements(self::$selectors['css'], $selector);
	}

	function screenshot($save_to, array $options=[]) {
		$options = array_merge([
			'overwrite' => true,
			'base64'    => false,
		], $options);
		$img = base64_decode($this->session->screenshot());
		if ($options['base64'])
			return $img;
		if (!$options['overwrite'] && file_exists($save_to))
			throw new Exception("File exists, cannot save screenshot");
		if (!file_put_contents($save_to, $img))
			throw new Exception("Failed to save screenshot (write failure).");
		return $this;
	}

	function screenshot_element($element, $outputImage, array $options=[]) {
		$options = array_merge([
			'padding' => 0,
		], $options);
		$size = $element->size();
		$l = $element->location();
		//$l = $element->location_in_view();

		$w = take($size, 'width') + $options['padding'];
		$h = take($size, 'height') + $options['padding'];
		$x = take($l, 'x') - $options['padding'];
		$y = take($l, 'y') - $options['padding'];

		$img_str = $this->screenshot(null, ['base64' => true]);
		$im      = imagecreatefromstring($img_str);
		$portion = imagecreatetruecolor($w, $h);
		
		list($src_width, $src_height) = getimagesizefromstring($img_str);
		imagecopyresampled($portion, $im, 0, 0, $x, $y, $w, $h, $w, $h);
		imagepng($portion, $outputImage);
	}

	function type($el, $text) {
		$el->sendKeys($text);
		return $this;
	}

	function url() {
		return $this->session->url();
	}

	function forward() {
		$this->session->forward();
		return $this;
	}

	function back() {
		$this->session->back();
		return $this;
	}

	function refresh() {
		$this->session->refresh();
		return $this;
	}

	function frame() {
		return $this->session->frame();
	}

	/*
      'execute' => 'POST',
      'execute_async' => 'POST',
      'frame' => 'POST',
      'screenshot' => 'GET',
      'window_handle' => 'GET',
      'window_handles' => 'GET',
      'source' => 'GET',
      'title' => 'GET',
      'keys' => 'POST',
      'orientation' => array('GET', 'POST'),
      'alert_text' => array('GET', 'POST'),
      'accept_alert' => 'POST',
      'dismiss_alert' => 'POST',
      'moveto' => 'POST',
      'click' => 'POST',
      'buttondown' => 'POST',
      'buttonup' => 'POST',
      'doubleclick' => 'POST',
      'location' => array('GET', 'POST'),
      'file' => 'POST',
	 */

	static function kill_xvfb() {
		$process = shell_exec("ps aux | grep 'Xvfb'");
		preg_match('#(?P<process>.*Xvfb.*)#', $process, $matches);
		preg_match('#(\S+)[ ]+(?P<pid>\S+)#', take($matches, 'process'), $matches);
		$pid = take($matches, 'pid');
		if (strlen($pid > 3)) {
			shell_exec("sudo kill {$pid}");
		}
	}

	static function find_selenium_pid() {
		$dir = config::$setting['vendor_dir'];
		$process = shell_exec("ps aux | grep '{$dir}/selenium'");
		preg_match('#(?P<process>.*java -jar.*)#', $process, $matches);
		preg_match('#(\S+)[ ]+(?P<pid>\S+)#', take($matches, 'process'), $matches);
		return take($matches, 'pid');
	}

	static function kill_selenium() {
		$pid = false;
		for ($i = 0; $i < 5; $i++) {
			if (strlen($pid = self::find_selenium_pid())) {
				shell_exec("sudo kill {$pid}");
			}
		}
		self::kill_xvfb();
		return $pid;
	}

	static function start_selenium() {
		if (strlen(self::find_selenium_pid())) return false;
		$version = self::SELENIUM_VERSION;
		$selenium = config::$setting['vendor_dir']."/selenium/selenium-server-standalone-{$version}.jar";
		$cmd = "sudo /usr/bin/xvfb-run /usr/bin/java -jar {$selenium} > /dev/null 2>&1 &";
		shell_exec($cmd);
		sleep(15);
		return true;
	}

}
