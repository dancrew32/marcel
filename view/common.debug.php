<? if (isset($memory) && isset($unit) && isset($runtime)): ?>
<h3>Memory</h3>
<ul>
	<li><?= $memory ?><?= $unit ?></li>
	<li><?= $runtime ?></li>
</ul>
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
	<li><strong>Branch</strong>:
	<?= html::a($branch_url, $branch) ?></li>
	<li><strong>Diff</strong>: <?= $git->diff_stat() ?></li>
</ul>
<? endif ?>
