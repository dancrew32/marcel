<div class="row">
	<div class="span8">

		<div class="clearfix">
			<h1 class="pull-left">
				Product Categories
				<? echoif($total, "<small>{$total} Total</small>") ?>
			</h1>
		</div>

		<? echoif(note::get('product_category:add'), 
			html::alert('Successfully added Product Category', ['type'=>'success'])) ?>
		<? echoif(note::get('product_category:edit'), 
			html::alert('Successfully updated Product Category', ['type'=>'success'])) ?>
		<? echoif(note::get('product_category:delete'), 
			html::alert('Successfully deleted Product Category', ['type'=>'success'])) ?>

		<? if ($total): ?>
			<? foreach ($product_categories as $product_category): ?>
				<div class="media well">
					<?= r('product_category', 'view', [ 'product_category' => $product_category ]) ?>
				</div>
			<? endforeach ?>
		<? else: ?>
			<p class="lead">
				No Product Categories yet!
				<br>
				Use the "Add Category" form to create a new product category!
			</p>
		<? endif ?>
		&nbsp;

		<?= $pager ?>

	</div>
	<div class="span4">
		<h2>
			Add Category
		</h2>
		<div class="well">
			<?= r('product_category', 'add_form') ?>
		</div>

		<?= r('product', 'sub_nav') ?>
	</div>
</div>
