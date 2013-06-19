<?
class controller_git extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Git Home');
		auth::only(['git']);
		parent::__construct($o);
   	}

	function main() {
		$git = git::open(ROOT_DIR);
		$out = $git->run('status');
		pp($out);
	}
}
