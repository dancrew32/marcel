<div class="media-body">
	<h4 class="media-heading">
		<?= $product_type ?>
		<span class="label pull-right product-type-<?= take($product_type, 'slug') ?>">
			<?= $product_type->category ?>
		</span>
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
