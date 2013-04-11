<div class="pagination"><ul>
	<li>
		<? if ($page != 1): ?>
			<span class="prev">
				<a href="<?= $base.$prev.$suffix ?>">
					&#x2190; prev
				</a>
			</span>
		<? else: ?>
			<span class="prev">
				<span>
					&#x2190; prev
				</span>
			</span>
		<? endif ?>
	</li>
	
	<li>
		<? if ($page != 1): ?>
			<a href="<?= "{$base}1{$suffix}" ?>">1</a>
		<? else: ?>
			<span class="current">1</span>
		<? endif ?>
	</li>
	
	<li>
		<? if ($start > 2): ?>
			<span class="ellipses">
				&hellip;
			</span>
		<? endif ?>
	</li>
	
	<? $min = min($end, $num - 1) ?>
	<? for ($p = ($start == 1 ? 2 : $start); $p <= $min; $p++): ?>
		<li>
			<? if ($p == $page): ?>
				<span class="current">
					<?= $p ?>
				</span>
			<? else: ?>
				<a href="<?= $base.$p.$suffix ?>">
					<?= $p ?>
				</a>
			<? endif ?>
		</li>
	<? endfor ?>
	
	<? if ($num - $end > 1): ?>
		<li>
			<span class="ellipses">
				&hellip;
			</span>
		</li>
	<? endif ?>
	
	<? if ($page < $num): ?>
		<li>
			<a href="<?= $base.$num.$suffix ?>">
				<?= $num ?>
			</a>
		</li>
		<li>
			<span class="next">
				<a href="<?= $base.($page + 1).$suffix ?>">
					next &#x2192;
				</a>
			</span>
		</li>
	<? elseif ($page > $num): ?>
		<? if ($num != 1): ?>
			<li>
				<a href="<?= $base.$num.$suffix ?>">
					<?= $num ?>
				</a>
			</li>
		<? endif ?>
			<li>
				<span class="next">
					<span>
						next &#x2192;
					</span>
				</span>
			</li>
	<? else: ?>
		<? if ($num != 1): ?>
			<li>
				<span class="current">
					<?= $num ?>
				</span>
			</li>
		<? endif ?>
		
		</li>
			<span class="next">
				<span>
					next &#x2192;
				</span>
			</span>
		</li>
	<? endif ?>
</ul></div>
