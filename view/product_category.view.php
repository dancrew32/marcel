<div class="media-body">
	<h4 class="media-heading">
		<?= $product_category ?>

		<small>
			(<?= take($product_category, 'slug') ?>)
		</small>

		<span class="label pull-right product-category-<?= take($product_category, 'slug') ?>">
			<?= $product_category ?>
		</span>
	</h4>
	<ul class="nav nav-pills last">
		<? if ($mode != 'edit'): ?>
			<li>
				<?= html::a([
					'href' => "{$root_path}/edit/{$product_category->id}", 
					'text' => "Edit",
					'icon' => 'edit',
				]) ?>
			<li>
		<? endif ?>
			<?= html::a([
				'href' => "{$root_path}/delete/{$product_category->id}", 
				'text' => "Delete",
				'icon' => 'trash',
			]) ?>
		</li>
	</ul>
</div>
