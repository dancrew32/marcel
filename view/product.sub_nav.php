<ul class="nav nav-tabs nav-stacked">
	<? foreach ($items as $item): ?>
		<li>
			<?= html::a($item) ?>
		</li>
	<? endforeach ?>
</ul>
