<div class="row">
	<div class="span8">

		<div class="clearfix">
			<h1 class="pull-left">
				Users
				<? echoif($total, "<small>{$total} Total</small>") ?>
			</h1>

			<?= r('common', 'output_style', [
				'output_style' => $output_style,
				'root_path'    => $root_path,
				'page'         => $page,
			]) ?>
		</div>

		<? echoif(note::get('user:add'), html::alert('Successfully added User', ['type'=>'success'])) ?>
		<? echoif(note::get('user:edit'), html::alert('Successfully updated User', ['type'=>'success'])) ?>
		<? echoif(note::get('user:delete'), html::alert('Successfully deleted User', ['type'=>'success'])) ?>
		<? if ($total): ?>
			<? if ($output_style == 'table'): ?>

				<table class="table table-condensed table-striped table-hover">
					<?= r('user', 'table', [ 'users' => $users ]) ?>
				</table>

			<? elseif ($output_style == 'media'): ?>

				<? foreach ($users as $user): ?>
					<div class="media well">
						<?= r('user', 'view', [ 'user' => $user ]) ?>
					</div>
				<? endforeach ?>

			<? endif ?>
		<? else: ?>
			<p class="lead">
				No users yet!
				<br>
				Use the "Add User" form to create a new user!
			</p>
		<? endif ?>
		&nbsp;
		<?= $pager ?>
	</div>
	<div class="span4">
		<h2>
			Add User
		</h2>
		<div class="well">
			<?= r('user', 'add_form') ?>
		</div>
	</div>
</div>
