<div id="stripe-data"
	data-pub-key="<?= credit::get_public_key() ?>"></div>

<? pp(credit::get_months()) ?>
<? pp(credit::get_years()) ?>
<? #pp(credit::charge()) ?>

