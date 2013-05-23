<div class="row">
	<div class="span8">
		<h1>
			Edit Feature
		</h1>
		<div class="well">
			<?= r('feature', 'edit_form', [ 'feature' => $feature ]) ?>
		</div>
	</div>
	<div class="span4">
		<h2>
			Current Feature
		</h2>
		<div class="media well">
			<?= r('feature', 'view', [ 'feature' => $feature, 'mode' => 'edit' ]) ?>
		</div>
	</div>
</div>
