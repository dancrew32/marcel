<?
class controller_main extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Home');
		parent::__construct($o);
   	}

	function index() {
		echo "I live";
	}
}
