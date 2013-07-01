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

		<?= r('product', 'sub_nav') ?>
	</div>
</div>
