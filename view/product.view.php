<div class="media-body">
	<h4 class="media-heading">
		<?= $product ?>

		<? if ($product_type): ?>
			<small>
				(<?= $product_type ?>)
			</small>
		<? endif ?>

		<? if ($product_category): ?>
			<span class="label pull-right product-type-<?= take($product_type, 'slug') ?>">
				<?= $product_type->category ?>
			</span>
		<? endif ?>
	</h4>
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
