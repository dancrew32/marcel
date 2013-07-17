<div class="media-body">
	<h4 class="media-heading">
		<?= $product_category ?>

		<small>
			(<?= take($product_category, 'slug') ?>)
		</small>

		<span class="label label-<?= $type_count ? 'info' : 'warning' ?> pull-right <?= take($product_category, 'slug') ?>">
			<?= $type_count ?> Total
		</span>
	</h4>

	<p>
		<?= html::btn(route::get('Product Type Home'), 'Add Some Types', 'plus') ?>
	</p>

	<? if ($type_count): ?>
		<ul class="nav nav-pills">
			<? foreach ($types as $type): ?>
				<li>
					<?= html::a(route::get('Product Type Edit', ['id' => $type->id]), $type, 'tag') ?>
				</li>
			<? endforeach ?>
		</ul>
	<? else: ?>
		<p class="text-warning">
			This category has no types and is safe to 
			<?= html::a(route::get('Product Category Delete', ['id' => $product_category->id]), 'delete') ?>.
		</p>
	<? endif ?>

	<ul class="nav nav-pills last">
		<? if ($mode != 'edit'): ?>
			<li>
				<?= html::a([
					'href' => route::get('Product Category Edit', ['id' => $product_category->id]), 
					'text' => "Edit",
					'icon' => 'edit',
				]) ?>
			<li>
		<? endif ?>
			<?= html::a([
				'href' => route::get('Product Category Delete', ['id' => $product_category->id]), 
				'text' => "Delete",
				'icon' => 'trash',
			]) ?>
		</li>
	</ul>
</div>
