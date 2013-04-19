<h3><?= h($cron->name) ?></h3>
<ul>
	<li>
		<?= $cron->active ? 'Active' : 'Inactive' ?>
	</li>
	<li>
		<?= h($cron->script) ?>
	</li>
	<li>
		<?= h($cron->frequency) ?>
		<br>
		<?= $cron->should_run() ? "run" : "don't run" ?>
	</li>
	<li>
		<?= html::a("/cron/edit/{$cron->id}", "Edit") ?>
	</li>
	<li>
		<?= html::a("/cron/delete/{$cron->id}", "Delete") ?>
	</li>
</ul>
