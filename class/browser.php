<?
class browser {

	# https://github.com/Element-34/php-webdriver

	const SELENIUM_VERSION = '2.33.0';
	const SELENIUM_PORT = 4444;

	private $instance = false;

	function __construct() {
		require_once VENDOR_DIR.'/webdriver/PHPWebDriver/__init__.php';
		$wd_host = 'http:'.BASE_URL.':'.self::SELENIUM_PORT.'/wd/hub';
		$this->instance = new PHPWebDriver_WebDriver($wd_host);
		return $this;
	}

	function session($browser='firefox') {
		return $this->instance->session($browser);
	}

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
		$dir = VENDOR_DIR;
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
		$selenium = VENDOR_DIR."/selenium/selenium-server-standalone-{$version}.jar";
		$cmd = "sudo /usr/bin/xvfb-run /usr/bin/java -jar {$selenium} > /dev/null 2>&1 &";
		shell_exec($cmd);
		sleep(15);
		return true;
	}

}
