<div class="row">
	<div class="span8">

		<div class="clearfix">
			<h1 class="pull-left">
				Products
				<? echoif($total, "<small>{$total} Total</small>") ?>
			</h1>

			<?= r('common', 'output_style', [
				'output_style' => $output_style,
				'root_path'    => $root_path,
				'page'         => $page,
			]) ?>
		</div>

		<? echoif(note::get('product:add'), html::alert('Successfully added Product', ['type'=>'success'])) ?>
		<? echoif(note::get('product:edit'), html::alert('Successfully updated Product', ['type'=>'success'])) ?>
		<? echoif(note::get('product:delete'), html::alert('Successfully deleted Product', ['type'=>'success'])) ?>
		<? if ($total): ?>
			<? if ($output_style == 'table'): ?>

				<table class="table table-condensed table-striped table-hover">
					<?= r('product', 'table', [ 'products' => $products ]) ?>
				</table>

			<? elseif ($output_style == 'media'): ?>

				<? foreach ($products as $product): ?>
					<div class="media well">
						<?= r('product', 'view', [ 'product' => $product ]) ?>
					</div>
				<? endforeach ?>

			<? endif ?>
		<? else: ?>
			<p class="lead">
				No products yet!
				<br>
				Use the "Add Product" form to create a new product!
			</p>
		<? endif ?>
		&nbsp;
		<?= $pager ?>
	</div>
	<div class="span4">
		<h2>
			Add Product
		</h2>
		<div class="well">
			<?= r('product', 'add_form') ?>
		</div>
	</div>
</div>
