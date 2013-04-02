<?
class controller_common extends controller_base {

	function debug($o) {
		if (!DEBUG || !auth::is_admin()) return $this->skip();
		$this->memory = round(memory_get_usage(false) / 1000);
		$this->unit = "Kb";
		$this->runtime = (round(microtime(true) - START_TIME, 4)).'s';
		$this->memcache_stats = cache::mc()->getStats();
	}

	function index() {
	}

	function not_found() {
		$code   = 404;
		$status = 'Not Found';
		header("HTTP/1.0 {$code} {$status}");
		die(json_encode([
			'status' =>	$status,
			'code'   => $code,
		]));
	}	

	function login() {
		$this->action = "/login";
		$this->user = User::$user;
		$u = take($_POST, 'user');
		$p = take($_POST, 'pass');
		$this->fields = [
			'user' => [
				'type' => 'text',
				'placeholder' => 'Email',
				'value' => $u,
			],
			'pass' => [
				'type' => 'password',
				'placeholder' => 'Password',
			],
		];

		if ($this->is_post) {
			$to = User::login($u, $p);
			app::redir($to ? '/' : '/login');
		}
	}

	function logout() {
		User::logout();
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

}
