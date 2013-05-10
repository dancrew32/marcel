<?
# Routes
app::$routes = [
	
	# Root
	'/' => [ 'c' => 'common', 'm' => 'index', 'name' => 'Home', 'section' => 'Home'],

	# Image Processing path (Skip database initialization with `nodb`)
	'/i' => [ 'c' => 'image', 'm' => 'process', 'nodb' => true, 'name' => 'Image Process' ],

	# Login/Join/Logout
	'/login'  => [ 
		'http' => [ 
			'get'  => [ 'c' => 'authentication', 'm' => 'main' ],
			'post' => [ 'c' => 'authentication', 'm' => 'login_try' ],
		],
		'name' => 'Login',
	],
	'/join'   => [
		'http' => [
			'get'  => [ 'c' => 'authentication', 'm' => 'main' ],
			'post' => [ 'c' => 'authentication', 'm' => 'create_user' ],
		], 'name' => 'Join',
	],
	'/logout' => [ 'c' => 'authentication', 'm' => 'logout', 'name' => 'Logout' ],

	# Cron Jobs
	'/cron(?:/*)(?P<page>[0-9]*)'   => [ 'c' => 'cron_job', 'm' => 'all', 'name' => 'Cron Home', 'section' => 'Cron' ],
	'/cron/add'                     => [ 'c' => 'cron_job', 'm' => 'add', 'section' => 'Cron' ],
	'/cron/scripts'                 => [ 'c' => 'cron_job', 'm' => 'scripts', 'section' => 'Cron' ],
	'/cron/edit/(?P<id>([0-9]+))'   => [ 'c' => 'cron_job', 'm' => 'edit', 'section' => 'Cron' ],
	'/cron/delete/(?P<id>([0-9]+))' => [ 'c' => 'cron_job', 'm' => 'delete', 'section' => 'Cron' ],

	# Workers
	'/workers/reset/(?P<id>([0-9]+))'    => [ 'c' => 'worker', 'm' => 'reset', 'section' => 'Worker' ],
	'/workers/delete/(?P<id>([0-9]+))'   => [ 'c' => 'worker', 'm' => 'delete', 'section' => 'Worker' ],
	'/workers(?:/*)(?P<filter>([a-z]*))' => [ 'c' => 'worker', 'm' => 'all', 'name' => 'Worker Home', 'section' => 'Worker' ],

	# Users
	'/users/delete/(?P<id>([0-9]+))' 
		=> [ 'c' => 'user', 'm' => 'delete', 'section' => 'User' ],
	'/users/add'   
		=> [ 'c' => 'user', 'm' => 'add', 'section' => 'User' ],
	'/users/edit/(?P<id>([0-9]+))'  
		=> [ 'c' => 'user', 'm' => 'edit', 'section' => 'User' ],
	'/users(?:/*)(?P<page>[0-9]*)(?:/*)(?P<filter>([a-z]*))(?P<format>\.*[a-z]*)' 
		=> [ 'c' => 'user', 'm' => 'all', 'name' => 'User Home', 'section' => 'User' ],

	# Cart Example
	'/cart' 
		=> [ 'c' => 'cart', 'm' => 'main', 'name' => 'Cart Home', 'section' => 'Cart' ],
	'/cart/add/(?P<key>[0-9]+)(?:/*)(?P<amount>[0-9\*]*)' 
		=> [ 'c' => 'cart', 'm' => 'add' ],
	'/cart/remove/(?P<key>[0-9]+)(?:/*)(?P<amount>[0-9\*]*)' 
		=> [ 'c' => 'cart', 'm' => 'remove' ],
	'/cart/checkout' 
		=> [ 'c' => 'cart', 'm' => 'checkout' ],
	'/cart/quantity' 
		=> [ 'c' => 'cart', 'm' => 'quantity' ],
	'/cart/thank-you' 
		=> [ 'c' => 'cart', 'm' => 'success', 'name' => 'Checkout Success' ],

	# Product
	'/products/delete/(?P<id>([0-9]+))' 
		=> [ 'c' => 'product', 'm' => 'delete', 'section' => 'Product' ],
	'/products/add'   
		=> [ 'c' => 'product', 'm' => 'add', 'section' => 'Product' ],
	'/products/edit/(?P<id>([0-9]+))'  
		=> [ 'c' => 'product', 'm' => 'edit', 'section' => 'Product' ],
	'/products(?:/*)(?P<page>[0-9]*)(?:/*)(?P<filter>([a-z]*))(?P<format>\.*[a-z]*)' 
		=> [ 'c' => 'product', 'm' => 'all', 'name' => 'Product Home', 'section' => 'Product' ],

	# Product Types
	'/product-types/delete/(?P<id>([0-9]+))' 
		=> [ 'c' => 'product_type', 'm' => 'delete', 'section' => 'Product Type' ],
	'/product-types/add'   
		=> [ 'c' => 'product_type', 'm' => 'add', 'section' => 'Product Type' ],
	'/product-types/edit/(?P<id>([0-9]+))'  
		=> [ 'c' => 'product_type', 'm' => 'edit', 'section' => 'Product Type' ],
	'/product-types(?:/*)(?P<page>[0-9]*)(?:/*)(?P<filter>([a-z]*))(?P<format>\.*[a-z]*)' 
		=> [ 'c' => 'product_type', 'm' => 'all', 'name' => 'Product Type Home', 'section' => 'Product Type' ],

	# Product Categories
	'/product-categories/delete/(?P<id>([0-9]+))' 
		=> [ 'c' => 'product_category', 'm' => 'delete', 'section' => 'Product Category' ],
	'/product-categories/add'   
		=> [ 'c' => 'product_category', 'm' => 'add', 'section' => 'Product Category' ],
	'/product-categories/edit/(?P<id>([0-9]+))'  
		=> [ 'c' => 'product_category', 'm' => 'edit', 'section' => 'Product Category' ],
	'/product-categories(?:/*)(?P<page>[0-9]*)(?:/*)(?P<filter>([a-z]*))(?P<format>\.*[a-z]*)' 
		=> [ 'c' => 'product_category', 'm' => 'all', 'name' => 'Product Category Home', 'section' => 'Product Category' ],

	# Geocoder
	'/geo' => [ 'c' => 'geo', 'm' => 'code', 'name' => 'Geocode' ],

	# Messaging
	'/message' => [ 'c' => 'message', 'm' => 'main', 'name' => 'Message Home', 'section' => 'Message' ],

	# HTTP Example
	'/http' => [
		'http' => [
			'get'    => [ 'c' => 'http_test', 'm' => 'get' ],
			'post'   => [ 'c' => 'http_test', 'm' => 'post' ],
			'put'    => [ 'c' => 'http_test', 'm' => 'put' ],
			'delete' => [ 'c' => 'http_test', 'm' => 'delete' ],
		],
	],

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

	# Mustache view test
	'/mustache' => [ 'c' => 'mustachetest', 'm' => 'main', 'name' => 'Mustache Home', 'section' => 'Mustache' ],

	# Error
	'/403' => [ 'c' => 'status_code', 'm' => 'forbidden', 'nodb' => true ],
	'/404' => [ 'c' => 'status_code', 'm' => 'not_found', 'nodb' => true ],
	'/500' => [ 'c' => 'status_code', 'm' => 'fatal_error', 'nodb' => true ],

];
