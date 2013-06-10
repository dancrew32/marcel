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
	<? foreach($queries as $k => $v): ?>
		<? pp($k.' &middot; '.implode(', ', (array)$v)) ?>
	<? endforeach ?>
<? endif ?>
<? if (isset($route_cache)): ?>
<h3>Lookups <code>route::get</code></h3>
<? pp($route_cache) ?>
<? endif ?>
