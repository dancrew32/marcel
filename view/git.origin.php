<? if ($ahead): ?>
	Your branch is <?= $ahead ?> <?= $ahead == 1 ? 'commit' : 'commits' ?> ahead of origin.
<? else: ?>
	Your branch is up-to-date with origin.	
<? endif ?>
