<?

# Routes
route::$routes = [
	
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
	'/hybrid' => [ 'c' => 'authentication', 'm' => 'hybrid' ],

];

# Workers
route::$routes += [
	"/workers/reset/". route::ID 
		=> [ 'c' => 'worker', 'm' => 'reset', 'name' => 'Worker Reset', 'section' => 'Worker' ],
	"/workers/delete/". route::ID 
		=> [ 'c' => 'worker', 'm' => 'delete', 'name' => 'Worker Delete', 'section' => 'Worker' ],
	'/workers(?:/*)(?P<filter>([a-z]*))' 
		=> [ 'c' => 'worker', 'm' => 'all', 'name' => 'Worker Home', 'section' => 'Worker' ],
];


# Cron Jobs
route::$routes += ['/cron/scripts' => [ 'c' => 'cron_job', 'm' => 'scripts', 'section' => 'Cron' ]];
route::$routes += route::crud('/cron', 'cron_job', 'Cron', 'Cron');

# Users
route::$routes += ['/users/masquerade/begin/'. route::ID =>
	[ 'c' => 'user', 'm' => 'masquerade_begin', 'name' => 'User Masquerade Begin', 'section' => 'User' ]];
route::$routes += ['/users/masquerade/end/' =>
	[ 'c' => 'user', 'm' => 'masquerade_end', 'name' => 'User Masquerade End', 'section' => 'User' ]];
route::$routes += route::crud('/users', 'user', 'User', 'User');

# User Types
route::$routes += route::crud('/user-types', 'user_type', 'User Type', 'User Type');

# Features
route::$routes += route::crud('/features', 'feature', 'Feature', 'Features');

# Product
route::$routes += route::crud('/products', 'product', 'Product', 'Product');

# Product Types
route::$routes += route::crud('/product-types', 'product_type', 'Product Type', 'Product Type');

# Product Categories
route::$routes += route::crud('/product-categories', 'product_category', 'Product Category', 'Product Category');

# User Verification
route::$routes += [
	'/verify/user/resend(?:/*)(?P<id>[0-9]*)' 
		=> [ 'c' => 'user_verification', 'm' => 'resend', 'name' => 'User Verification Resend' ], 
	'/verify/user(?:/*)(?P<hash>[^/]*)(?:/*)(?P<user_id>[0-9]+)' 
		=> [ 'c' => 'user_verification', 'm' => 'verify', 'name' => 'User Verification' ], 
];

route::$routes += [

	# User Permissions
	'/user-permissions/update' 
		=> [ 'c' => 'user_permission', 'm' => 'update', ],
	"/user-permissions". route::PAGE_FILTER_FORMAT
		=> [ 'c' => 'user_permission', 'm' => 'all', 'name' => 'User Permission Home', 'section' => 'User Permission' ],

	# Cart Example
	'/cart' 
		=> [ 'c' => 'cart', 'm' => 'main', 'name' => 'Cart Home', 'section' => 'Cart' ],
	'/cart/add/(?P<key>[0-9]+)(?:/*)(?P<amount>[0-9\*]*)' 
		=> [ 'c' => 'cart', 'm' => 'add', 'name' => 'Cart Add', 'section' => 'Cart' ],
	'/cart/remove/(?P<key>[0-9]+)(?:/*)(?P<amount>[0-9\*]*)' 
		=> [ 'c' => 'cart', 'm' => 'remove', 'name' => 'Cart Remove', 'section' => 'Cart' ],
	'/cart/checkout' 
		=> [ 'c' => 'cart', 'm' => 'checkout', 'name' => 'Cart Checkout', 'section' => 'Cart' ],
	'/cart/quantity' 
		=> [ 'c' => 'cart', 'm' => 'quantity', 'name' => 'Cart Quantity', 'section' => 'Cart' ],
	'/cart/thank-you' 
		=> [ 'c' => 'cart', 'm' => 'success', 'name' => 'Checkout Success', 'section' => 'Cart' ],
	
	# Shipping
	'/shipping'
		=> [ 'c' => 'shipping', 'm' => 'main', 'name' => 'Shipping Home' ],

	# Stocks
	'/stocks/(?P<symbols>[a-zA-Z,]+)' => [ 'c' => 'stock', 'm' => 'main', 'name' => 'Stock Home' ],

	# Geocoder
	'/geo' => [ 'c' => 'geo', 'm' => 'code', 'name' => 'Geocode' ],

	# Messaging
	'/message' => [ 'c' => 'message', 'm' => 'main', 'name' => 'Message Home', 'section' => 'Message' ],

	# Phone Test
	'/phone'         => [ 'c' => 'phonetest', 'm' => 'phone', 'name' => 'Phone Home', 'section' => 'Test' ],
	'/phone/program' => [ 'c' => 'phonetest', 'm' => 'program', 'name' => 'Twilio Read' ],
	'/phone/record'  => [ 'c' => 'phonetest', 'm' => 'recording', 'name' => 'Twilio Record' ],

	# HTTP Example
	'/http' => [
		'http' => [
			'get'    => [ 'c' => 'http_test', 'm' => 'get' ],
			'post'   => [ 'c' => 'http_test', 'm' => 'post' ],
			'put'    => [ 'c' => 'http_test', 'm' => 'put' ],
			'delete' => [ 'c' => 'http_test', 'm' => 'delete' ],
		],
	],

	# Mustache view test
	'/mustache' => [ 'c' => 'mustachetest', 'm' => 'main', 'name' => 'Mustache Home', 'section' => 'Message' ],

	# Markdown view test
	'/markdown' => [ 'c' => 'markdowntest', 'm' => 'main', 'name' => 'Markdown Home', 'section' => 'Message' ],

	# CAPTCHA
	'/captcha' => [ 
		'http' => [
			'get'  => [ 'c' => 'captcha', 'm' => 'get' ],
			'post' => [ 'c' => 'captcha', 'm' => 'post' ],
		],
		'name' => 'Captcha Home',
	],

	# OCR
	'/ocr' => [ 'c' => 'ocr', 'm' => 'get', 'name' => 'OCR Home', 'section' => 'Test' ],

	# Filter
	'/filters' => [ 'c' => 'filter', 'm' => 'main', 'name' => 'Filter Home', 'section' => 'Test' ],

	# Routes
	//'/routes' => [ 'c' => 'common', 'm' => 'routes', 'nodb' => true ],

	# Linode
	'/linode' => [ 'c' => 'linode', 'm' => 'main', 'name' => 'Linode Home', 'section' => 'Linode' ],

	# Git
	'/git' 
		=> [ 'c' => 'git', 'm' => 'main', 'name' => 'Git Home', 'section' => 'Git' ],
	'/git/commit' 
		=> [ 'c' => 'git', 'm' => 'commit', 'name' => 'Git Commit', 'section' => 'Git' ],
	//'/git/commit/edit' 
		//=> [ 'c' => 'git', 'm' => 'commit_edit', 'name' => 'Git Commit Edit', 'section' => 'Git' ],
	'/git/stage/(?P<files>[a-zA-Z0-9/_\.\-,]+)' 
		=> [ 'c' => 'git', 'm' => 'stage', 'name' => 'Git Stage', 'section' => 'Git' ],
	'/git/unstage/(?P<files>[a-zA-Z0-9/_\.\-,]+)'
		=> [ 'c' => 'git', 'm' => 'unstage', 'name' => 'Git Unstage', 'section' => 'Git' ],
	'/git/push/(?P<branch>[a-zA-Z0-9_\-]+)'
		=> [ 'c' => 'git', 'm' => 'push', 'name' => 'Git Push', 'section' => 'Git' ],
	'/git/pull/(?P<branch>[a-zA-Z0-9_\-]+)'
		=> [ 'c' => 'git', 'm' => 'pull', 'name' => 'Git Pull', 'section' => 'Git' ],
	'/git/fetch/(?P<branch>[a-zA-Z0-9_\-]+)'
		=> [ 'c' => 'git', 'm' => 'fetch', 'name' => 'Git Fetch', 'section' => 'Git' ],
	'/git/branch/add'
		=> [ 'c' => 'git', 'm' => 'branch_add', 'name' => 'Git Branch Add', 'section' => 'Git' ],
	'/git/branch/delete/(?P<branch>[a-zA-Z0-9_\-]+)'
		=> [ 'c' => 'git', 'm' => 'branch_delete', 'name' => 'Git Branch Delete', 'section' => 'Git' ],
	'/git/branch/checkout/(?P<branch>[a-zA-Z0-9_\-]+)'
		=> [ 'c' => 'git', 'm' => 'branch_checkout', 'name' => 'Git Branch Checkout', 'section' => 'Git' ],
	'/git/submodule/add'
		=> [ 'c' => 'git', 'm' => 'submodule_add', 'name' => 'Git Submodule Add', 'section' => 'Git' ],
	'/git/submodule/delete/(?P<path>[a-zA-Z0-9/_\.\-,]+)'
		=> [ 'c' => 'git', 'm' => 'submodule_delete', 'name' => 'Git Submodule Delete', 'section' => 'Git' ],
	# reset not working yet
	//'/git/reset/(?P<files>[a-zA-Z0-9/_\.\-,]+)'
		//=> [ 'c' => 'git', 'm' => 'reset', 'name' => 'Git Reset', 'section' => 'Git' ],

	# File Manager
	'/files/upload' 
		=> [ 'c' => 'file_manager', 'm' => 'upload', 'name' => 'File Manager Upload', 'section' => 'File Manager' ],
	'/files/search' 
		=> [ 'c' => 'file_manager', 'm' => 'search', 'name' => 'File Manager Search', 'section' => 'File Manager' ],
	'/files(?:/*)(?P<path>[a-zA-Z0-9\.\-_ /]*).edit' 
		=> [ 'c' => 'file_manager', 'm' => 'edit', 'name' => 'File Manager Edit', 'section' => 'File Manager' ],
	'/files(?:/*)(?P<path>[a-zA-Z0-9\.\-_ /]*)' 
		=> [ 'c' => 'file_manager', 'm' => 'main', 'name' => 'File Manager Home', 'section' => 'File Manager' ],

	# Goodreads
	'/goodreads' 
		=> [ 'c' => 'test', 'm' => 'goodreads', 'name' => 'Goodreads Home', 'section' => 'Goodreads' ],

	# Craigslist
	'/craigslist' 
		=> [ 'c' => 'test', 'm' => 'craigslist' ],

	# Error
	'/403' => [ 'c' => 'status_code', 'm' => 'forbidden', 'nodb' => true ],
	'/404' => [ 'c' => 'status_code', 'm' => 'not_found', 'nodb' => true ],
	'/500' => [ 'c' => 'status_code', 'm' => 'fatal_error', 'nodb' => true ],

];
