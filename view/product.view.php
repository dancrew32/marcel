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

	<p>
		<strong>	
			Price:
		</strong>
		$<?= number_format($product->price, 2) ?>
	</p>

	<ul class="nav nav-pills last">
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
	</ul>
</div>
