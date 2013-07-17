<? if ($ahead): ?>
	Your branch is
	<strong class="text-warning">
		<?= $ahead ?> <?= $ahead == 1 ? 'commit' : 'commits' ?>
	</strong>
   	ahead of origin.
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
	<li>
		<?= html::a($fetch_url, 'Fetch from Master', 'chevron-down') ?>
	</li>
</ul>
