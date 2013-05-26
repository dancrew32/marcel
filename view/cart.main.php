<div class="row">
	<div class="span8">
		<h1>
			Checkout
			<? if ($has_items): ?>
				<small>
					<?= $total_items ?>
					<?= $total_items == 1 ? 'Item' : 'Items' ?> in <?= strtolower(Cart::NAME) ?>.
				</small>
			<? endif ?>
		</h1>

		<? echoif(note::get(Cart::MAIN.':add'), 
			html::alert('Successfully added item to '. strtolower(Cart::NAME) .'.', ['type'=>'success'])) ?>
		<? echoif(note::get(Cart::MAIN.':remove'), 
			html::alert('Successfully removed item from '. strtolower(Cart::NAME) .'.', ['type'=>'success'])) ?>

		<?= r('cart', 'checkout_form') ?>

		<div id="stripe-data"
			data-pub-key="<?= credit::get_public_key() ?>"></div>
	</div>

	<div class="span4">

		<h2>
			Your <?= Cart::NAME ?>
		</h2>

		<?= r('cart', 'view', [ 'items' => $items ]) ?>
		<? /*
		<h2>
			Add Items
		</h2>
		<ul class="nav nav-tabs nav-stacked">
			<li>
				<?= html::a((app::get_path('Cart Home')."/add/{$test_product_id}"), "Add Item") ?>
			</li>
			<li>
				<?= html::a((app::get_path('Cart Home')."/add/{$test_product_id}/3"), "Add 3 Items") ?>
			</li>
			<li>
				<?= html::a((app::get_path('Cart Home')."/remove/{$test_product_id}"), "Remove Item") ?>
			</li>
			<li>
				<?= html::a((app::get_path('Cart Home')."/remove/{$test_product_id}/*"), "Remove All") ?>
			</li>
			<li>
				<?= html::a((app::get_path('Cart Home')."/remove/{$test_product_id}/3"), "Remove 3") ?>
			</li>
		</ul>
		<? pp(Cart::get_type(Cart::MAIN)) ?>
		*/ ?>
	</div>
</div>
