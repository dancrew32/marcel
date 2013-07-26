<?
class controller_base {
	public $skip = false;
	function __construct($o) { }
	function skip() {
		$this->skip = true;	
	}
	function redir($url=null) {
		app::redir($url ? $url : $this->root_path);
	}
}
