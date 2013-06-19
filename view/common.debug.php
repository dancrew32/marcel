<? if (isset($memory) && isset($unit)): ?>
	<?= $memory ?><?= $unit ?>
	<br>
<? endif ?>
<? if (isset($runtime)): ?>
	<?= $runtime ?>
	<br>
<? endif ?>
<? if (isset($memcache_stats)): ?>
	<? pp($memcache_stats) ?>
<? endif ?>
<? if (isset($queries)): ?>
<? pp($queries) ?>
<? endif ?>
<? if (isset($route_cache)): ?>
<h3>Lookups <code>route::get</code></h3>
<? pp($route_cache) ?>
<? endif ?>
<? if (isset($git)): ?>
<h3>Git</h3>
<ul>
	<li><strong>Branch</strong>: <?= $git->active_branch() ?></li>
</ul>
<? endif ?>
