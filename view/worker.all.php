<div class="tabbable tabs-right row">

	<div class="tab-content span8">
		<h1>	
			<?= $filter != 'All' ? "{$filter} " : '' ?>Workers
			<? echoif($total, "<small>{$total} Total</small>") ?>
		</h1>
		<? if ($total): ?>
			<? foreach ($workers as $worker): ?>
				<div class="media well">
					<?= r('worker', 'view', [ 'worker' => $worker ]) ?>
				</div>
			<? endforeach ?>
		<? else: ?>
			<p class="lead">
				There don't appear to be any
				<?= $filter != 'All' ? ('<em>'. strtolower($filter) .'</em> ') : '' ?>workers
				in the queue.
			</p>
		<? endif ?>
	</div>

	<div class="span4">
		<h2>
			Filter Workers
		</h2>
		<ul class="nav nav-tabs nav-stacked">
			<li<? echoif($filter == 'All', ' class="active"') ?>>
				<?= html::a([
					'href' => "{$root_path}",
					'text' => "All",
					'icon' => 'eye-open',
				]) ?>
			</li>
			<li<? echoif($filter == 'Active', ' class="active"') ?>>
				<?= html::a([
					'href' => "{$root_path}/active",
					'text' => "Active",
					'icon' => 'ok-circle',
				]) ?>
			</li>
			<li<? echoif($filter == 'Scheduled', ' class="active"') ?>>
				<?= html::a([
					'href' => "{$root_path}/scheduled",
					'text' => "Scheduled",
					'icon' => 'calendar',
				]) ?>
			</li>
		</ul>
	</div>

</div>
