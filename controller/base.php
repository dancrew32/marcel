<?
class controller_base {
	public $skip = false;
	function __construct($o) { }
	function skip() {
		$this->skip = true;	
	}
}
