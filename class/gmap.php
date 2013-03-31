<?
class gmap {
	const API_KEY = '<your google maps api key>';	

	static function add_js($callback='onMapsLoaded') {
		$key = self::API_KEY;
		$js = "https://maps.googleapis.com/maps/api/js?key={$key}&sensor=true&callback={$callback}";
		app::asset($js, 'js');
	}

	static function geocode($address) {
		$key = self::API_KEY;
		$address = urlencode($address);
		$api = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&sensor=false";
		return json_decode(file_get_contents($api));
	}
}
