<div class="row">
	<div class="span8">
		<h1>
			Edit Product Category
		</h1>
		<div class="well">
			<?= r('product_category', 'edit_form', [ 'product_category' => $product_category ]) ?>
		</div>
	</div>
	<div class="span4">
		<h2>
			Current Category
		</h2>
		<div class="media well">
			<?= r('product_category', 'view', [ 'product_category' => $product_category, 'mode' => 'edit' ]) ?>
		</div>

		<ul class="nav nav-tabs nav-stacked">
			<li>
				<?= html::a([
					'href' => "{$root_path}",
					'text' => "View All Product Categories",
					'icon' => 'eye-open',
				]) ?>
			</li>
			<li>
				<?= html::a([
					'href' => app::get_path('Product Home'),
					'text' => "View All Products",
					'icon' => 'eye-open',
				]) ?>
			</li>
		</ul>
	</div>
</div>
