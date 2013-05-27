<?
class controller_common extends controller_base {

	function index() {
		// see views/common.index.php
	}

	function auth_test() {
		die('allowed');
	}

	function ocr() {
		//$img = file_get_contents(IMAGE_DIR.'/ocr/foo.png');
		$img = file_get_contents('http://l.danmasq.com/img/ocr/test.jpg');
		//$img = file_get_contents('http://l.danmasq.com/captcha');
		//$img = file_get_contents('http://www.google.com/recaptcha/static/images/recaptcha-example.gif');
		//$img = file_get_contents('https://www.google.com/recaptcha/api/image?c=03AHJ_VutYzGVKMOVUT1PL3OVWnLETuVG_e_ghv5TaZ-5svD_pa_fNvboMdxaQOf1_TJUGebg6Fpps6uBE0wmu50f998YpbPTGFc3V250terylAL2Quf7KbCrODCR2DhDHE50DCLqxxCgAcbIrVm4N9IGAclijx8uIG9_9UeSqzuCPUF9q2enLixw');
		$tmp = TMP_DIR.'/ocr/temp.png';
		file_put_contents($tmp, $img);
		echo ocr::get($tmp);
		unlink($tmp);
	}

	function nav() {
		$this->logged_in = User::$logged_in;	
		$this->admin_nav_sections = [
			'Cron', 
			'Feature',
			'Product Type', 
			'Product Category', 
			'Shipping', 
			'User', 
			'User Permission',
			'User Type',
			'Worker',
		];
		$this->admin_nav = [
			'cron_job' => [
				'text' => 'Cron Manager', 
				'path' => 'Cron Home',
			],
			'feature' => [
				'text' => 'Features', 
				'path' => 'Feature Home',
			],
			'product_category' => [
				'text' => 'Product Categories', 
				'path' => 'Product Category Home',
			],
			'product_type' => [
				'text' => 'Product Types', 
				'path' => 'Product Type Home',
			],
			'shipping' => [
				'text' => 'Shipping', 
				'path' => 'Shipping Home',
			],
			'user' => [
				'text' => 'Users', 
				'path' => 'User Home',
			],
			'user_permission' => [
				'text' => 'User Permissions', 
				'path' => 'User Permission Home',
			],
			'user_type' => [
				'text' => 'User Types', 
				'path' => 'User Type Home',
			],
			'worker' => [
				'text' => 'Workers', 
				'path' => 'Worker Home',
			],
		];
	}

	function debug($o) {
		if (!DEBUG) return $this->skip();
		$this->memory = round(memory_get_usage(false) / 1000);
		$this->unit = "Kb";
		$this->runtime = (round(microtime(true) - START_TIME, 4)).'s';
		//$this->memcache_stats = cache::mc()->getStats();
		//$this->queries = $GLOBALS['DEBUG_QUERIES'];
	}

	function pager($o) {
		$this->total  = take($o, 'total', 0);
		$this->rpp    = take($o, 'rpp', 0);
		$this->page   = take($o, 'page', 0);
		$this->base   = take($o, 'base', '/');
		$this->suffix = take($o, 'suffix');

		if (!$this->total || !$this->rpp || !$this->page)
			return $this->skip();
		
		$this->num = ceil($this->total / $this->rpp);
		
		if ($this->num == 1 && $this->page == 1)
			return $this->skip();
		
		$this->start = $this->page - 2;
		$this->end   = $this->page + 2;
		
		# Show more if we're near the start 
		if ($this->start < 1) {
			$this->end += (1 - $this->start);
			$this->start = 1;
		}
		
		# Show more if we're near the end
		if ($this->end > $this->num) {
			$this->end = $this->num;
			$this->start = $this->end - 4;
			if ($this->start < 1)
				$this->start = 1;
		}
		
		if ($this->page != 1) {
			$this->prev = $this->page - 1;
		    if ($this->prev > $this->num)
		        $this->prev = $this->num;
		}
	}

	function output_style($o) {
		$this->output_style = take($o, 'output_style');
		$this->root_path    = take($o, 'root_path');
		$this->page         = take($o, 'page');
	}

	function routes() {
		json(app::$routes);
	}

}
