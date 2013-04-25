<div class="row">
	<div class="span8">
		<h1>
			Edit User
		</h1>
		<div class="well">
			<?= r('user', 'edit_form', [ 'user' => $user ]) ?>
		</div>
	</div>
	<div class="span4">
		<h2>
			Current User
		</h2>
		<div class="media well">
			<?= r('user', 'view', [ 'user' => $user, 'mode' => 'edit' ]) ?>
		</div>
	</div>
</div>
