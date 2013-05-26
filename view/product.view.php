<div class="media-body">
	<h4 class="media-heading">
		<?= $product ?>

		<? if ($product_type): ?>
			<small>
				(<?= html::a(app::get_path('Product Type Home') ."/edit/{$product_type->id}", $product_type) ?>)
			</small>
		<? endif ?>

		<? if ($product_category): ?>
			<a href="<?= app::get_path('Product Category Home') ."/edit/{$product_category->id}" ?>"
				class="label pull-right product-category-<?= take($product_category, 'slug') ?>">
				<?= $product_category ?>
			</a>
		<? else: ?>
			<span class="label label-important pull-right">
				Missing Category
			</span>
		<? endif ?>
	</h4>

	<p<? echoif(!$product->price, ' class="text-warning"') ?>>
		<strong>	
			Price:
		</strong>
		$<?= $product->price_pretty() ?>
	</p>

	<p<? echoif(!$product->active, ' class="muted"')?>>
		<strong>	
			Status:
		</strong>
		<?= $product->active ? 'Active' : 'Inactive' ?>
	</p>

	<? if ($product->description): ?>
		<p>
			<?= util::truncate($product->description, size::ONE_TWEET)  ?>
		</p>
	<? endif ?>

	<ul class="nav nav-pills last">
		<? if (auth::can(['product'])): ?>
			<? if ($mode != 'edit'): ?>
				<li>
					<?= html::a([
						'href' => "{$root_path}/edit/{$product->id}", 
						'text' => "Edit",
						'icon' => 'edit',
					]) ?>
				<li>
			<? endif ?>
				<?= html::a([
					'href' => "{$root_path}/delete/{$product->id}", 
					'text' => "Delete",
					'icon' => 'trash',
				]) ?>
			</li>
		<? endif ?>

		<? if (auth::can(['cart'])): ?>
			<li>
				<?= html::a([
					'href' => $product->add_url(),
					'text' => "Add to ". Cart::NAME,
					'icon' => 'plus-sign',
				]) ?>
			<li>
		<? endif ?>
	</ul>
</div>
