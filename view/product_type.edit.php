<div class="row">
	<div class="span8">
		<h1>
			Edit Product Type
		</h1>
		<div class="well">
			<?= r('product_type', 'edit_form', [ 'product_type' => $product_type ]) ?>
		</div>
	</div>
	<div class="span4">
		<h2>
			Current Type
		</h2>
		<div class="media well">
			<?= r('product_type', 'view', [ 'product_type' => $product_type, 'mode' => 'edit' ]) ?>
		</div>

		<ul class="nav nav-tabs nav-stacked">
			<li>
				<?= html::a([
					'href' => "{$root_path}",
					'text' => "View All Product Types",
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
