<div class="row">
	<div class="span8">

		<div class="clearfix">
			<h1 class="pull-left">
				Product Types
				<? echoif($total, "<small>{$total} Total</small>") ?>
			</h1>
		</div>

		<? echoif(note::get('product_type:add'), 
			html::alert('Successfully added Product Type', ['type'=>'success'])) ?>
		<? echoif(note::get('product_type:edit'), 
			html::alert('Successfully updated Product Type', ['type'=>'success'])) ?>
		<? echoif(note::get('product_type:delete'), 
			html::alert('Successfully deleted Product Type', ['type'=>'success'])) ?>

		<? if ($total): ?>
			<? foreach ($product_types as $product_type): ?>
				<div class="media well">
					<?= r('product_type', 'view', [ 'product_type' => $product_type ]) ?>
				</div>
			<? endforeach ?>
		<? else: ?>
			<p class="lead">
				No Product Types yet!
				<br>
				Use the "Add Product Type" form to create a new product type!
			</p>
		<? endif ?>
		&nbsp;

		<?= $pager ?>

	</div>
	<div class="span4">
		<h2>
			Add Type
		</h2>
		<div class="well">
			<?= r('product_type', 'add_form') ?>
		</div>

		<?= r('product', 'sub_nav') ?>
	</div>
</div>
