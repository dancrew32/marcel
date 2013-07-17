<?
class controller_common extends controller_base {

	function index() {
		// see views/common.index.php
	}

	# no view
	function css() {
		app::asset('core/screen', 'css');
		echo css::get_html();
	}

	# no view
	function js() {
		$jquery_version = '1.9.1';
		$out  = '<script src="'. JS_DIR .'/loader.js"></script>';
		$out .= "<script>\$LAB.script('//ajax.googleapis.com/ajax/libs/jquery/{$jquery_version}/jquery.min.js')";
		$out .= ".script('". JS_DIR ."/bootstrap.js').wait()";
		$out .= ".script('". JS_DIR ."/class/app.js')";
		foreach (array_unique(app::$assets['js']) as $j) {
			$delim = strpos($j, '?') ? '&' : '?';
			$j = CACHE_BUST ? $j.$delim.'d='.date('U') : $j;
			$out .= '.script("'. $j .'")';
		}
		echo $out . '</script>';
	}

	function nav() {
		$this->is_masquerading = take($_SESSION, 'masquerader');
   	}

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
			'file_manager' => [
				'text' => 'File Manager', 
				'path' => 'File Manager Home',
			],
			'git' => [
				'text' => 'Git', 
				'path' => 'Git Home',
			],
			'linode' => [
				'text' => 'Linodes', 
				'path' => 'Linode Home',
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
			'File Manager',
			'Git',
			'Linode',
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
		if (auth::can(['git'])) {
			$this->git = git::open(ROOT_DIR);
			$this->branch = $this->git->active_branch();
			$this->branch_url = "{$this->git->github_url()}/tree/{$this->branch}";
			$this->diff_stat = $this->git->diff_stat();
		}
		//$this->memcache_stats = cache::mc()->getStats();
		//$this->queries = $GLOBALS['_db_queries'];
		//$this->route_cache = route::$get_cache;
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
