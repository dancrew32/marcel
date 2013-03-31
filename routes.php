<?
# Routes
app::$routes = [
	
	# Root
	'/' => [ 'c' => 'common', 'm' => 'index' ],

	# Error
	'/404' => [ 'c' => 'common', 'm' => 'not_found' ],

];
