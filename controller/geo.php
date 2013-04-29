<?
class controller_geo extends controller_base {
	function __construct($o) {
		$this->root_path = app::get_path('Geocode');
		parent::__construct($o);
   	}

	function code($o) {
		$query = take($_POST, 'query');
		$out = gmap::geocode($query);
		$data = [];
		foreach ($out->results as $result)
			$data[] = $result->formatted_address;
		json($data);
	}
}
