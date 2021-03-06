<div class="media-body">
	<h4 class="media-heading">
		<?= h(take($worker, 'class')) ?>::<?= h(take($worker, 'method')) ?>
		<span class="label pull-right label-<?= $worker->active ? 'success' : 'muted' ?>">
			<?= $worker->active ? 'Processing' : 'Queued' ?>
		</span>
		<? if ($worker->run_at): ?>
			<span class="label pull-right label-info">
				Scheduled
			</span>
		<? endif ?>
	</h4>
	<p>
		<strong class="muted">
			Queued
		</strong>
		<?= time::ago($worker->created_at) ?>
		<? if ($worker->active): ?>
			&amp;	
			<strong class="text-success">
				Processing
			</strong>
			since <?= time::ago($worker->updated_at) ?>.
		<? elseif ($worker->run_at): ?>
			&amp;
			<strong class="text-info">
				scheduled to run in
			</strong>
			<?= time::ago($worker->run_at) ?>.
		<? endif ?>
	</p>

	<p class="scrolling">
		<? if (isset($worker->args{0})): ?>
			<? pp(unserialize($worker->args)) ?>
		<? else: ?>
			<em class="muted">No Args</em>
		<? endif ?>
	</p>

	<ul class="nav nav-pills last">
		<li>
			<?= html::a([
				'href' => route::get('Worker Reset', ['id' => $worker->id]), 
				'text' => "Reset",
				'icon' => 'refresh',
			]) ?>
		</li>
		<li>
			<?= html::a([
				'href' => route::get('Worker Delete', ['id' => $worker->id]), 
				'text' => "Delete",
				'icon' => 'trash',
			]) ?>
		</li>
	</ul>
</div>
