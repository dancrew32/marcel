<?
class controller_email extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Email Home');
		# auth::only(['email']);
		parent::__construct($o);
   	}

	function incoming_test($o) {
		foreach ($o as $k => $v)
			$this->{$k} = $v;
	}
}
