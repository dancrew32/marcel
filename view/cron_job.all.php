<div class="row">
	<div class="span8">
		<? foreach ($crons as $cron): ?>
			<div class="cron">
				<?= r('cron_job', 'view', [ 'cron' => $cron ]) ?>
			</div>
		<? endforeach ?>
		&nbsp;
		<?= $pager ?>
	</div>
	<div class="span4">
		<?= r('cron_job', 'add_form') ?>
	</div>
</div>
