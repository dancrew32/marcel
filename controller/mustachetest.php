<?
class controller_mustachetest extends controller_base {
	function __construct($o) {
		parent::__construct($o);
   	}

	function main() {
	}

	function template() {
		$this->test = date('H:m:s');
		$users = User::all();
		$this->user = [];
		foreach ($users as $u)
			$this->user[] = [
				'first' => $u->first,
				'last'  => $u->last,
			];
		if (util::is_ajax())
			json($this);
	}


}
