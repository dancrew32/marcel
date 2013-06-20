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

	const MARCEL_REMOTE_KEY = 'marcel';

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
		foreach ($pipes as $pipe)
			fclose($pipe);

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
		foreach ($pipes as $pipe)
			fclose($pipe);

		$status = trim(proc_close($resource));
		if ($status) throw new Exception($stderr);

		return $stdout;
	}

	public function run($command, $debug=false) {
		$cmd = Git::get_bin()." ".$command;
		if ($debug)
			pd($cmd);
		return $this->run_command($cmd);
	}

	public function reset($files) {
		if (is_array($files))
			$files = '"'.implode('" "', $files).'"';
		# TODO: permissions issues
		return $this->run("checkout HEAD -- $files");
	}

	public function stage($files = "*") {
		if (is_array($files))
			$files = '"'.implode('" "', $files).'"';
		return $this->run("add $files -v");
	}

	public function unstage($files = "*") {
		if (is_array($files))
			$files = '"'.implode('" "', $files).'"';
		return $this->run("checkout --rm $files");
	}

	public function commit($message = "") {
		return $this->run("commit -v -m ".escapeshellarg($message));
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

	public function diff_stat() {
		 return $this->run('diff --shortstat');
	}

	public function diff($file) {
		try {
			return $this->run("diff {$file}");
		} catch(Exception $e) {
			return null;
		}
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

	public function remote_url() {
		return $this->run('config --get remote.origin.url');
	}

	public function github_url($credentials='') {
		$out = util::explode_pop(':', $this->remote_url());
		$out = util::explode_shift('.git', "https://{$credentials}github.com/{$out}");
		return $out;
	}

	public function github_commit_url($hash) {
		return "{$this->github_url()}/commit/{$hash}";
	}

	private function establish_marcel_remote($remote) {
		$key = self::MARCEL_REMOTE_KEY;
		try {
			$this->run("remote add {$key} {$remote}");
			$origin_fetch = $this->run("config --get remote.origin.fetch");
			$this->run("config remote.{$key}.fetch {$origin_fetch}");
		} catch(Exception $e) {
			# already exists, ignore
	   	}
	}

	private function revoke_marcel_remote() {
		$key = self::MARCEL_REMOTE_KEY;
		try {
			$this->run("remote rm {$key}");
		} catch(Exception $e) {
			# didn't exist, ignore
	   	}
	}

	public function push($branch) {
		$api = api::get_key('github');
		$credentials = "{$api['username']}:{$api['password']}@";
		$remote = $this->github_url($credentials);
		$this->establish_marcel_remote($remote);
		$key = self::MARCEL_REMOTE_KEY;
		$out = $this->run("push {$key} {$branch}");
		$this->revoke_marcel_remote();
		return $out;
	}
	
	public function pull($remote='origin', $branch) {
		return $this->run("pull $remote $branch");
	}

	public function ahead_origin() {
		preg_match('/ahead (?P<ahead>[0-9]+)/', $this->run('branch -v -v'), $matches);
		return take($matches, 'ahead', 0);
	}

	public function log_simple($limit=5) {
		$log = $this->run("log --pretty=oneline --abbrev-commit -{$limit}");
		$lines = explode("\n", $log);
		$ahead = $this->ahead_origin();
		array_pop($lines);
		$commits = [];
		foreach ($lines as $k => $line) {
			$parts = explode(' ', $line);
			$hash = array_shift($parts);
			$is_head = $k == $ahead;
			$commits[] = [
				'is_head' => $is_head, 
				'hash'    => $hash,
				'message' => implode(' ', $parts),
			];
		}
		return $commits;
	}

	public function status() {
		$status = $this->run('status --porcelain');
		$status = explode("\n", $status);
		$out = [];

		foreach ($status as $s) {
			if (!isset($s{0}) && !isset($s{1})) continue;
			$file = util::explode_pop(' ', $s);
			switch ($s{0}.$s{1}) {
				case 'M ':
				case 'A ':
					$type = 'staged';
				break;
				case ' M':
					 $type = 'modified';
				break;
				case '??':
					$type = 'untracked';
				break;
			}
			if (!isset($type)) continue;
			$out[$type][] = $file;
		}
		return $out;
	}

	public function set_description($new) {
		$file = "{$this->repo_path}/.git/description";
		file_put_contents($file, $new);
	}

	public function get_description() {
		return file_get_contents($this->repo_path."/.git/description");
	}
	
}
