<?
class controller_base {
	public $skip = false;
	public $is_post = false;
	function __construct($o) {
		$this->is_post = take($o, 'm') == 'post';
   	}
	function skip() {
		$this->skip = true;	
	}
}
