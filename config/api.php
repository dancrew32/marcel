<?
api::$keys = [
	'stripe' => [ # https://manage.stripe.com/account/apikeys
		'DEV' => [
			'secret' => 'sk_test_',
			'public' => 'pk_test_',
		],
		'LIVE' => [
			'secret' => 'sk_live_',
			'public' => 'pk_live_',
		],
	],
	'google_maps' => [ # https://code.google.com/apis/console/b/0/
		'DEV'  => '',
		'LIVE' => '',
	],
	'usps' => [ # https://secure.shippingapis.com/registration/
		'DEV' => [
			'username' => '',
			'password' => '',
		],
		'LIVE' => [
			'username' => '',
			'password' => '',
		],
	],
];
