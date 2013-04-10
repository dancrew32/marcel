<?
class controller_common extends controller_base {

	function index() {
		// see views/common.index.php
	}

	function auth_test() {
		die('allowed');
	}

	function media_rows() {
	
	}

	function nav() {
	
	}

	function not_found() {
		$code   = 404;
		$status = 'Not Found';
		header("HTTP/1.1 {$code} {$status}");
		json([
			'status' =>	$status,
			'code'   => $code,
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

}
