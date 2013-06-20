<? if ($ahead): ?>
	Your branch is <?= $ahead ?> <?= $ahead == 1 ? 'commit' : 'commits' ?> ahead of origin.
	<ul class="nav nav-pills">
		<li>
			<?= html::a($push_url, 'Push to Master', 'arrow-up') ?>
		</li>
	</ul>
<? else: ?>
	Your branch is up-to-date with origin.	
<? endif ?>
