<?
# Routes
app::$routes = [
	
	# Root
	'/' => [ 'c' => 'common', 'm' => 'index'],

	# Login/Logout
	'/login'  => [ 'c' => 'authentication', 'm' => 'login' ],
	'/logout' => [ 'c' => 'authentication', 'm' => 'logout' ],

	# Cron Jobs
	'/cron(?:/*)(?P<page>[0-9]*)'   => [ 'c' => 'cron_job', 'm' => 'all' ],
	'/cron/add'                     => [ 'c' => 'cron_job', 'm' => 'add' ],
	'/cron/scripts'                 => [ 'c' => 'cron_job', 'm' => 'scripts' ],
	'/cron/edit/(?P<id>([0-9]+))'   => [ 'c' => 'cron_job', 'm' => 'edit' ],
	'/cron/delete/(?P<id>([0-9]+))' => [ 'c' => 'cron_job', 'm' => 'delete' ],

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
