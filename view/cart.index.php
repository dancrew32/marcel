<div class="row">
	<div class="span8">
		<h1>
			Checkout
			<? if ($has_items): ?>
				<small>
					<?= $total_items ?>
					<?= $total_items == 1 ? 'Item' : 'Items' ?> in Cart.
				</small>
			<? endif ?>
		</h1>
		<? echoif(note::get('cart:a:add'), html::alert('Successfully added item to cart.', ['type'=>'success'])) ?>
		<? echoif(note::get('cart:a:remove'), html::alert('Successfully removed item from cart.', ['type'=>'success'])) ?>
		<?= r('cart', 'checkout_form') ?>
	</div>
	<div class="span4">
		<h2>
			Add Items
		</h2>

		<ul class="nav nav-tabs nav-stacked">
			<li>
				<?= html::a((app::get_path('Cart Home')."/add/1"), "Add Item") ?>
			</li>
			<li>
				<?= html::a((app::get_path('Cart Home')."/add/1/3"), "Add 3 Items") ?>
			</li>
			<li>
				<?= html::a((app::get_path('Cart Home')."/remove/1"), "Remove Item") ?>
			</li>
			<li>
				<?= html::a((app::get_path('Cart Home')."/remove/1/*"), "Remove All") ?>
			</li>
			<li>
				<?= html::a((app::get_path('Cart Home')."/remove/1/3"), "Remove 3") ?>
			</li>
		</ul>
		<? pp(Cart::get_type('cart:a')) ?>
	</div>
</div>
<div id="stripe-data"
	data-pub-key="<?= credit::get_public_key() ?>"></div>


<? /*
<? pp(credit::get_months()) ?>
<? pp(credit::get_years()) ?>
<? #pp(credit::charge()) ?>
*/ ?>


