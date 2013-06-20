<? if ($ahead): ?>
	Your branch is <?= $ahead ?> <?= $ahead == 1 ? 'commit' : 'commits' ?> ahead of origin.
<? else: ?>
	Your branch is up-to-date with origin.	
<? endif ?>

<ul class="nav nav-pills">
	<? if ($ahead): ?>
		<li>
			<?= html::a($push_url, 'Push to Master', 'arrow-up') ?>
		</li>
	<? endif ?>
	<li>
		<?= html::a($pull_url, 'Pull from Master', 'arrow-down') ?>
	</li>
</ul>
