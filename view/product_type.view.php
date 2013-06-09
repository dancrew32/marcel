<div class="media-body">
	<h4 class="media-heading">
		<?= $product_type ?>

		<small>
			(<?= $product_type->slug ?>)
		</small>
		
		<? if ($product_category): ?>
			<a href="<?= route::get('Product Category Home') ."/edit/{$product_category->id}" ?>" 
				class="label pull-right product-category-<?= take($product_type, 'slug') ?>">
				<?= $product_type->category ?>
			</a>
		<? else: ?>
			<span class="label label-important pull-right">
				Missing Category
			</span>
		<? endif ?>
	</h4>

	<ul class="nav nav-pills">
		<? foreach ($products as $product): ?>
			<li>
				<?= html::a([
					'href'  => app::get_path('Product Home') ."/edit/{$product->id}", 
					'text'  => $product, 
					'icon'  => 'gift', 
					'class' => $product->active ? '' : 'muted',
				]) ?>
			</li>
		<? endforeach ?>
	</ul>

	<ul class="nav nav-pills last">
		<? if ($mode != 'edit'): ?>
			<li>
				<?= html::a([
					'href' => route::get('Product Type Edit', ['id' => $product_type->id]),
					'text' => "Edit",
					'icon' => 'edit',
				]) ?>
			<li>
		<? endif ?>
			<?= html::a([
				'href' => route::get('Product Type Delete', ['id' => $product_type->id]),
				'text' => "Delete",
				'icon' => 'trash',
			]) ?>
		</li>
	</ul>
</div>
