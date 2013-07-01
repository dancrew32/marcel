<?
class gmap {
	static function add_js($callback='onMapsLoaded') {
		$key = api::get_key('google_maps');
		$js = "https://maps.googleapis.com/maps/api/js?key={$key}&sensor=true&callback={$callback}";
		app::asset($js, 'js');
	}

	static function geocode($address) {
		$key = api::get_key('google_maps');
		$address = urlencode($address);
		$api = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&sensor=true";
		return json_decode(file_get_contents($api));
	}
}
