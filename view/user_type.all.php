<div class="row">
	<div class="span8">

		<div class="clearfix">
			<h1 class="pull-left">
				User Types
				<? echoif($total, "<small>{$total} Total</small>") ?>
			</h1>
		</div>

		<? echoif(note::get('user_type:add'), html::alert('Successfully added User Type', ['type'=>'success'])) ?>
		<? echoif(note::get('user_type:edit'), html::alert('Successfully updated User Type', ['type'=>'success'])) ?>
		<? echoif(note::get('user_type:delete'), html::alert('Successfully deleted User Type', ['type'=>'success'])) ?>
		<? if ($total): ?>
			<? foreach ($user_types as $user_type): ?>
				<div class="media well">
					<?= r('user_type', 'view', [ 'user_type' => $user_type ]) ?>
				</div>
			<? endforeach ?>
		<? else: ?>
			<p class="lead">
				No user types yet!
				<br>
				Use the "Add Type" form to create a new user type!
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
			<?= r('user_type', 'add_form') ?>
		</div>
	</div>
</div>
