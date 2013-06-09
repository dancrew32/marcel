<?
class controller_stock extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Stock Home');
		auth::only(['stock']);
		parent::__construct($o);
   	}

	function main($o) {
		$symbols = explode(',', take($o['params'], 'symbols', 'Z'));
		$this->stocks = Stock::symbols($symbols);


		# TODO: get the rest of the descriptions from http://money.stackexchange.com
		//$this->stock_history = stock::history('Z');
		//pp($this->stock_history);
	}
}
