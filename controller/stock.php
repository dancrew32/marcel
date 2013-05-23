<?
class controller_stock extends controller_base {
	function __construct($o) {
		$this->root_path = app::get_path('Stock Home');
		//auth::only(['stock']);
		parent::__construct($o);
   	}

	function main() {
		//$this->stock_data = (array) stock::quote('Z');
		//ksort($this->stock_data);

		$this->stock_history = stock::history('Z');
		pp($this->stock_history);
	}
}
