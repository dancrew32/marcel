<? /*
<?= $memory ?><?= $unit ?>
<br>
<?= $runtime ?>
<? pp($memcache_stats) ?>
<? foreach($GLOBALS['DEBUG_QUERIES'] as $k => $v): ?>
<? pp($k.' &middot; '.implode(', ', (array)$v)) ?>
<? endforeach ?>
*/ ?>
