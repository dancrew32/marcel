<div class="container"><div class="navbar"><div class="navbar-inner">
<ul class="nav">
	<? foreach ($urls as $name => $url): ?>
		<li><a href="<?= $url ?>"><?= $name ?></a></li>
	<? endforeach ?>
</ul>
</div></div></div>
