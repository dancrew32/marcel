<div class="media-body">
	<h4 class="media-heading">
		<?= $product_type ?>
		
		<? if ($product_category): ?>
			<a href="<?= app::get_path('Product Category Home') ."/edit/{$product_category->id}" ?>" 
				class="label pull-right product-category-<?= take($product_type, 'slug') ?>">
				<?= $product_type->category ?>
			</a>
		<? else: ?>
			<span class="label label-important pull-right">
				Missing Category
			</span>
		<? endif ?>
	</h4>
	<ul class="nav nav-pills last">
		<? if ($mode != 'edit'): ?>
			<li>
				<?= html::a([
					'href' => "{$root_path}/edit/{$product_type->id}", 
					'text' => "Edit",
					'icon' => 'edit',
				]) ?>
			<li>
		<? endif ?>
			<?= html::a([
				'href' => "{$root_path}/delete/{$product_type->id}", 
				'text' => "Delete",
				'icon' => 'trash',
			]) ?>
		</li>
	</ul>
</div>
