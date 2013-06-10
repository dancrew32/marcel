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
			<?= r('worker', 'nav_filter', ['filter' => $filter]) ?>
		</ul>
	</div>

</div>
