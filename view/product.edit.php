<div class="row">
	<div class="span8">
		<h1>
			Edit Product
		</h1>
		<div class="well">
			<?= r('product', 'edit_form', [ 'product' => $product ]) ?>
		</div>
	</div>
	<div class="span4">
		<h2>
			Current Product
		</h2>
		<div class="media well">
			<?= r('product', 'view', [ 'product' => $product, 'mode' => 'edit' ]) ?>
		</div>

		<ul class="nav nav-tabs nav-stacked">
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
