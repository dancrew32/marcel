<?
class controller_base {
	public $skip = false;
	function __construct($o) {
   	}
	function __get($prop) {
		if (isset(app::$req_type) && "is_".app::$req_type == $prop)
			return true;
	}
	function skip() {
		$this->skip = true;	
	}
}
