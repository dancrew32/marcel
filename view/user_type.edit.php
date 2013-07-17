<div class="row">
	<div class="span8">
		<h1>
			Edit User Type
		</h1>
		<div class="well">
			<?= r('user_type', 'edit_form', [ 'user_type' => $user_type ]) ?>
		</div>
	</div>
	<div class="span4">
		<h2>
			Current Type
		</h2>
		<div class="media well">
			<?= r('user_type', 'view', [ 'user_type' => $user_type, 'mode' => 'edit' ]) ?>
		</div>
	</div>
</div>
