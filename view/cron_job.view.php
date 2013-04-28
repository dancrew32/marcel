<div class="media-body">
	<h4 class="media-heading">
		<?= take($cron, 'name') ?>
		<span class="label pull-right<?= $status_class ?>">
			<? if (!$exists): ?>
				Invalid Script
			<? elseif (!$cron->active): ?>
				Inactive
			<? else: ?>
				<?= $should_run ? 'Running' : 'Waiting' ?>
			<? endif ?>
		</span>
	</h4>
	<p>
		<strong>Script</strong>:
		<span class="text-<?= $script_class ?> word-wrap">
			<?= $cron->script ?>
		</span>
	</p>
	<p>
		<strong>Frequency</strong>:
		<?= $cron->frequency ?>
	</p>
	<? if (isset($cron->description{0})): ?>
		<p>
			<?= $cron->description ?>
		</p>
	<? endif ?>
	<ul class="nav nav-pills last">
		<? if ($mode != 'edit'): ?>
			<li>
				<?= html::a([
					'href' => "{$root_path}/edit/{$cron->id}", 
					'text' => "Edit",
					'icon' => 'edit',
				]) ?>
			</li>
		<? endif ?>
		<li>
			<?= html::a([
				'href' => "{$root_path}/delete/{$cron->id}", 
				'text' => "Delete",
				'icon' => 'trash',
			]) ?>
		</li>
	</ul>
</div>
