<div class="pager">
	<? if ($page != 1): ?>
		<span class="prev">
			<a href="<?= $base.$prev.$suffix ?>" class="in">
				&#x2190; prev
			</a>
		</span>
	<? else: ?>
		<span class="prev">
			<span class="in">
				&#x2190; prev
			</span>
		</span>
	<? endif ?>
	
	<? if ($page != 1): ?>
		<a href="<?= "{$base}1{$suffix}" ?>">1</a>
	<? else: ?>
		<span class="current">1</span>
	<? endif ?>
	
	<? if ($start > 2): ?>
		<span class="ellipses">
			&hellip;
		</span>
	<? endif ?>
	
	<? $min = min($end, $num - 1) ?>
	<? for ($p = ($start == 1 ? 2 : $start); $p <= $min; $p++): ?>
		<? if ($p == $page): ?>
			<span class="current">
				<?= $p ?>
			</span>
		<? else: ?>
			<a href="<?= $base.$p.$suffix ?>">
				<?= $p ?>
			</a>
		<? endif ?>
	<? endfor ?>
	
	<? if ($num - $end > 1): ?>
		<span class="ellipses">
			&hellip;
		</span>
	<? endif ?>
	
	<? if ($page < $num): ?>
		<a href="<?= $base.$num.$suffix ?>">
			<?= $num ?>
		</a>
		<span class="next">
			<a href="<?= $base.($page + 1).$suffix ?>" class="in">
				next &#x2192;
			</a>
		</span>
	<? elseif ($page > $num): ?>
		<? if ($num != 1): ?>
			<a href="<?= $base.$num.$suffix ?>">
				<?= $num ?>
			</a>
		<? endif ?>
		<span class="next">
			<span class="in">
				next &#x2192;
			</span>
		</span>
	<? else: ?>
		<? if ($num != 1): ?>
			<span class="current">
				<?= $num ?>
			</span>
		<? endif ?>
		
		<span class="next">
			<span class="in">
				next &#x2192;
			</span>
		</span>
	<? endif ?>
</div>
