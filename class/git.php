<?
class git {
	protected static $bin = '/usr/bin/git';
	public static function set_bin($path) {
		self::$bin = $path;
	}
	
	public static function get_bin() {
		return self::$bin;
	}

	public static function &create($repo_path, $source = null) {
		return GitRepo::create_new($repo_path, $source);
	}

	public static function open($repo_path) {
		return new GitRepo($repo_path);
	}

	public static function is_repo($var) {
		return (get_class($var) == 'GitRepo');
	}

}

class gitrepo {

	protected $repo_path = null;

	public static function &create_new($repo_path, $source = null) {
		if (is_dir($repo_path) && file_exists($repo_path."/.git") && is_dir($repo_path."/.git")) {
			throw new Exception('"'.$repo_path.'" is already a git repository');
		} else {
			$repo = new self($repo_path, true, false);
			if (is_string($source)) {
				$repo->clone_from($source);
			} else {
				$repo->run('init');
			}
			return $repo;
		}
	}

	public function __construct($repo_path = null, $create_new = false, $_init = true) {
		if (is_string($repo_path)) {
			$this->set_repo_path($repo_path, $create_new, $_init);
		}
	}

	public function set_repo_path($repo_path, $create_new = false, $_init = true) {
		if (is_string($repo_path)) {
			if ($new_path = realpath($repo_path)) {
				$repo_path = $new_path;
				if (is_dir($repo_path)) {
					if (file_exists($repo_path."/.git") && is_dir($repo_path."/.git")) {
						$this->repo_path = $repo_path;
					} else {
						if ($create_new) {
							$this->repo_path = $repo_path;
							if ($_init) {
								$this->run('init');
							}
						} else {
							throw new Exception('"'.$repo_path.'" is not a git repository');
						}
					}
				} else {
					throw new Exception('"'.$repo_path.'" is not a directory');
				}
			} else {
				if ($create_new) {
					if ($parent = realpath(dirname($repo_path))) {
						mkdir($repo_path);
						$this->repo_path = $repo_path;
						if ($_init) $this->run('init');
					} else {
						throw new Exception('cannot create repository in non-existent directory');
					}
				} else {
					throw new Exception('"'.$repo_path.'" does not exist');
				}
			}
		}
	}

	public function test_git() {
		$descriptorspec = array(
			1 => array('pipe', 'w'),
			2 => array('pipe', 'w'),
		);
		$pipes = array();
		$resource = proc_open(Git::get_bin(), $descriptorspec, $pipes);

		$stdout = stream_get_contents($pipes[1]);
		$stderr = stream_get_contents($pipes[2]);
		foreach ($pipes as $pipe) {
			fclose($pipe);
		}

		$status = trim(proc_close($resource));
		return ($status != 127);
	}

	protected function run_command($command) {
		$descriptorspec = array(
			1 => array('pipe', 'w'),
			2 => array('pipe', 'w'),
		);
		$pipes = array();
		$resource = proc_open($command, $descriptorspec, $pipes, $this->repo_path);

		$stdout = stream_get_contents($pipes[1]);
		$stderr = stream_get_contents($pipes[2]);
		foreach ($pipes as $pipe) {
			fclose($pipe);
		}

		$status = trim(proc_close($resource));
		if ($status) throw new Exception($stderr);

		return $stdout;
	}

	public function run($command) {
		return $this->run_command(Git::get_bin()." ".$command);
	}

	public function add($files = "*") {
		if (is_array($files)) {
			$files = '"'.implode('" "', $files).'"';
		}
		return $this->run("add $files -v");
	}

	public function commit($message = "") {
		return $this->run("commit -av -m ".escapeshellarg($message));
	}

	public function clone_to($target) {
		return $this->run("clone --local ".$this->repo_path." $target");
	}

	public function clone_from($source) {
		return $this->run("clone --local $source ".$this->repo_path);
	}

	public function clone_remote($source) {
		return $this->run("clone $source ".$this->repo_path);
	}

	public function clean($dirs = false) {
		return $this->run("clean".(($dirs) ? " -d" : ""));
	}

	public function create_branch($branch) {
		return $this->run("branch $branch");
	}

	public function delete_branch($branch, $force = false) {
		return $this->run("branch ".(($force) ? '-D' : '-d')." $branch");
	}

	public function list_branches($keep_asterisk = false) {
		$branchArray = explode("\n", $this->run("branch"));
		foreach($branchArray as $i => &$branch) {
			$branch = trim($branch);
			if (! $keep_asterisk) {
				$branch = str_replace("* ", "", $branch);
			}
			if ($branch == "") {
				unset($branchArray[$i]);
			}
		}
		return $branchArray;
	}

	public function active_branch($keep_asterisk = false) {
		$branchArray = $this->list_branches(true);
		$active_branch = preg_grep("/^\*/", $branchArray);
		reset($active_branch);
		if ($keep_asterisk) {
			return current($active_branch);
		} else {
			return str_replace("* ", "", current($active_branch));
		}
	}

	public function checkout($branch) {
		return $this->run("checkout $branch");
	}

	public function merge($branch) {
		return $this->run("merge $branch --no-ff");
	}

	public function fetch() {
		return $this->run("fetch");
	}

	public function add_tag($tag, $message = null) {
		if ($message === null) {
			$message = $tag;
		}
		return $this->run("tag -a $tag -m $message");
	}

	public function push($remote, $branch) {
		return $this->run("push --tags $remote $branch");
	}
	
	public function pull($remote, $branch) {
		return $this->run("pull $remote $branch");
	}

	public function set_description($new) {
		file_put_contents($this->repo_path."/.git/description", $new);
	}

	public function get_description() {
		return file_get_contents($this->repo_path."/.git/description");
	}
	
}
