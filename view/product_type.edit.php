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
			Current Product Type
		</h2>
		<div class="media well">
			<?= r('product_type', 'view', [ 'product_type' => $product_type, 'mode' => 'edit' ]) ?>
		</div>
	</div>
</div>
