<div class="row">
	<div class="span8">
		<h1>
			Cron Jobs
			<? echoif($total, "<small>{$total} Total</small>") ?>
		</h1>
		<? echoif(note::get('cron_job:add'), html::alert('Successfully added Cron', ['type'=>'success'])) ?>
		<? echoif(note::get('cron_job:edit'), html::alert('Successfully updated Cron', ['type'=>'success'])) ?>
		<? echoif(note::get('cron_job:delete'), html::alert('Successfully deleted Cron', ['type'=>'success'])) ?>
		<? if ($total): ?>
			<? foreach ($crons as $cron): ?>
				<div class="media well">
					<?= r('cron_job', 'view', [ 'cron' => $cron ]) ?>
				</div>
			<? endforeach ?>
		<? else: ?>
			<p class="lead">
				No cron jobs yet!
				<br>
				Use the "Add Cron" form to create a new job!
			</p>
		<? endif ?>
		&nbsp;
		<?= $pager ?>
	</div>
	<div class="span4">
		<h2>
			Add Cron
		</h2>
		<div class="well">
			<?= r('cron_job', 'add_form') ?>
		</div>
	</div>
</div>
