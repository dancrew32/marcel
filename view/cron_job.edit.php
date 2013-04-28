<div class="row">
	<div class="span8">
		<h1>
			Edit Cron
		</h1>
		<div class="well">
			<?= r('cron_job', 'edit_form', [ 'cron' => $cron ]) ?>
		</div>
	</div>
	<div class="span4">
		<h2>
			Current Cron
		</h2>
		<div class="media well">
			<?= r('cron_job', 'view', [ 'cron' => $cron, 'mode' => 'edit' ]) ?>
		</div>
	</div>
</div>
