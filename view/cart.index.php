<div id="stripe-data"
	data-pub-key="<?= credit::get_public_key() ?>"></div>

<? echoif(note::get('cart:a:add'), html::alert('Successfully added item to cart.', ['type'=>'success'])) ?>
<? pp(User::$user->cart) ?>
<?= html::a((app::get_path('Cart Home')."/add/1"), "Add Item") ?>
<? pp(credit::get_months()) ?>
<? pp(credit::get_years()) ?>
<? #pp(credit::charge()) ?>


