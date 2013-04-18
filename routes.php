<?
# Routes
app::$routes = [
	
	# Root
	'/' => [ 'c' => 'common', 'm' => 'index'],

	# Login/Logout
	'/login'  => [ 'c' => 'authentication', 'm' => 'login' ],
	'/logout' => [ 'c' => 'authentication', 'm' => 'logout' ],

	# Cron Jobs
	'/cron' => [ 'c' => 'cron_job', 'm' => 'all' ],

	# HTTP Example
	'/http' => [
		'http' => [
			'get'    => [ 'c' => 'http_test', 'm' => 'get' ],
			'post'   => [ 'c' => 'http_test', 'm' => 'post' ],
			'put'    => [ 'c' => 'http_test', 'm' => 'put' ],
			'delete' => [ 'c' => 'http_test', 'm' => 'delete' ],
		],
	],

	# Skip database initialization with `nodb`
	'/i' => [ 'c' => 'image', 'm' => 'process', 'nodb' => true ],

	# Auth Example (anons not allowed)
	'/auth-test-simple' => [ 
		'c' => 'common', 'm' => 'auth_test',
		'auth' => ['user'],
	],
	'/auth-test-complex' => [ 
		'http' => [
			'get' => [
				'c' => 'common', 'm' => 'auth_test',
				'auth' => ['user'],
			],
		],
		'auth' => ['anon'],
	],

	# Error
	'/404' => [ 'c' => 'status_code', 'm' => 'not_found', 'nodb' => true ],
	'/500' => [ 'c' => 'status_code', 'm' => 'fatal_error', 'nodb' => true ],

];
