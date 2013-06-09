<div class="row">
	<div class="span8">

		<div class="clearfix">
			<h1 class="pull-left">
				Features
				<? echoif($total, "<small>{$total} Total</small>") ?>
			</h1>

			<?= r('common', 'output_style', [
				'output_style' => $output_style,
				'root_path'    => $root_path,
				'page'         => $page,
			]) ?>
		</div>

		<? echoif(note::get('feature:add'), 
			html::alert('Successfully added Feature', ['type'=>'success'])) ?>
		<? echoif(note::get('feature:edit'), 
			html::alert('Successfully updated Feature', ['type'=>'success'])) ?>
		<? echoif(note::get('feature:delete'), 
			html::alert('Successfully deleted Feature', ['type'=>'success'])) ?>

		<? if ($total): ?>
			<? if ($output_style == 'table'): ?>

				<table class="table table-condensed table-striped table-hover">
					<?= r('feature', 'table', [ 'features' => $features ]) ?>
				</table>

			<? elseif ($output_style == 'media'): ?>

				<? foreach ($features as $feature): ?>
					<div class="media well">
						<?= r('feature', 'view', [ 'feature' => $feature ]) ?>
					</div>
				<? endforeach ?>

			<? endif ?>
		<? else: ?>
			<p class="lead">
				No Features yet!
				<br>
				Use the "Add Feature" form to create a new feature!
			</p>
		<? endif ?>
		&nbsp;
		<?= $pager ?>
	</div>
	<div class="span4">
		<h2>
			Add Feature
		</h2>
		<div class="well">
			<?= r('feature', 'add_form') ?>
		</div>

		<ul class="nav nav-tabs nav-stacked">
			<li>
				<?= html::a(route::get('User Permission Home'), 'View User Permissions', 'eye-open') ?>
			</li>
		</ul>
	</div>
</div>
