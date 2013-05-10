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
		<?= html::btn(app::get_path('Product Type Home'), 'Add Some Types', 'plus') ?>
	</p>

	<? if ($type_count): ?>
		<ul class="nav nav-pills">
			<? foreach ($types as $type): ?>
				<li>
					<?= html::a(app::get_path('Product Type Home') ."/edit/{$type->id}", $type, 'tag') ?>
				</li>
			<? endforeach ?>
		</ul>
	<? else: ?>
		<p class="text-warning">
			This category has no types and is safe to 
			<?= html::a("{$root_path}/delete/{$product_category->id}", 'delete') ?>.
		</p>
	<? endif ?>

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
