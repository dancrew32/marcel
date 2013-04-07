<?
# Routes
app::$routes = [
	
	# Root
	'/' => [ 'c' => 'common', 'm' => 'index' ],

	# Login/Logout
	'/login'  => [ 'c' => 'common', 'm' => 'login' ],
	'/logout' => [ 'c' => 'common', 'm' => 'logout' ],


	# HTTP Example
	'/http' => [
		'http' => [
			'get'    => [ 'c' => 'http_test', 'm' => 'get' ],
			'post'   => [ 'c' => 'http_test', 'm' => 'post' ],
			'put'    => [ 'c' => 'http_test', 'm' => 'put' ],
			'delete' => [ 'c' => 'http_test', 'm' => 'delete' ],
		],
	],

	# On-the-fly Image Processing
	'/i' => [ 'c' => 'image', 'm' => 'process' ],

	# Error
	'/404' => [ 'c' => 'common', 'm' => 'not_found' ],

];
