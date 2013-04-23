<div class="row">
	<div class="span8">
		<h1>
			Users
			<? echoif($total, "<small>{$total} Total</small>") ?>
		</h1>
		<? echoif(note::get('user:add'), html::alert('Successfully added User', ['type'=>'success'])) ?>
		<? echoif(note::get('user:edit'), html::alert('Successfully updated User', ['type'=>'success'])) ?>
		<? echoif(note::get('user:delete'), html::alert('Successfully deleted User', ['type'=>'success'])) ?>
		<? if ($total): ?>
			<? if ($output_style == 'table'): ?>

				<table class="table table-condensed table-striped">
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
