<div class="row">
	<div class="span8">
		<h1>
			Git
			<? if ($count): ?>
				<small>
					<?= $count ?> <?= $count == 1 ? 'Commit' : 'Commits' ?>
				</small>
			<? endif ?>
		</h1>

		<? if (!$count): ?>
			<p class="lead">Your origin/master is empty. Push some code!</p>
		<? endif ?>

		<h3>Origin</h3>
		<?= r('git', 'origin') ?>

		<h3>Commit</h3>
		<?= r('git', 'commit_form') ?>

		<h3>Log</h3>
		<?= r('git', 'log_simple', ['count' => 5]) ?>

		<h3>Branches</h3>
		<?= r('git', 'branches') ?>
		<?= r('git', 'branch_add_form') ?>

		<h3>Submodules</h3>
		<?= r('git', 'submodules') ?>
		<?= r('git', 'submodule_add_form') ?>

	</div>
	<div class="span4">

		<h2>Status</h2>
		<?= r('git', 'status') ?>

	</div>
</div>
