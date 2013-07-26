<ul>
	<? foreach ($urls as $name => $url): ?>
		<li><a href="<?= $url ?>"><?= $name ?></a></li>
	<? endforeach ?>
</ul>
