<?
class controller_common extends controller_base {

	function index() {
		// see views/common.index.php
	}

	function nav() { }

	function nav_collapse() { }

	function nav_test() {
		$this->in_section = route::in_sections([
			'Message', 
			'Mustache', 
			'Markdown',
			'Test',
		]);
	}

	function nav_user() {
		$this->logged_in = User::$logged_in;	
		$this->user = User::$user;
	}

	function nav_admin() {
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

		if (!auth::can(array_keys($this->admin_nav))) 
			return $this->skip();

		$this->in_section = route::in_sections([
			'Cron', 
			'Feature',
			'Product Type', 
			'Product Category', 
			'Shipping', 
			'User', 
			'User Permission',
			'User Type',
			'Worker',
		]);
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
		json(route::$routes);
	}

}
