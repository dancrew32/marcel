<div id="stripe-data"
	data-pub-key="<?= credit::get_public_key() ?>"></div>

<?= html::a((app::get_path('Cart Home')."/add/1"), "Add Item") ?>
<? pp(credit::get_months()) ?>
<? pp(credit::get_years()) ?>
<? #pp(credit::charge()) ?>


