<?
api::$keys = [
	'stripe' => [ # https://manage.stripe.com/account/apikeys
		'DEV' => [
			'secret' => 'sk_test...',
			'public' => 'pk_test...',
		],
		'LIVE' => [
			'secret' => 'sk_live...',
			'public' => 'pk_live...',
		],
	],
	'google_maps' => [ # https://code.google.com/apis/console/b/0/
		'DEV'  => '',
		'LIVE' => '',
	],
];
