<? if ($staged || $modified || $untracked): ?>
	<? if ($staged): ?>
		<h3>Staged</h3>
		<?= r('git', 'status_staged', ['paths' => $staged]) ?>
	<? endif ?>

	<? if ($deleted): ?>
		<h3>Staged</h3>
		<?= r('git', 'status_deleted', ['paths' => $deleted]) ?>
	<? endif ?>

	<? if ($modified): ?>
		<h3>Modified</h3>
		<?= r('git', 'status_modified', ['paths' => $modified]) ?>
	<? endif ?>

	<? if ($untracked): ?>
		<h3>Untracked</h3>
		<?= r('git', 'status_untracked', ['paths' => $untracked]) ?>
	<? endif ?>
<? else: ?>
	<p class="lead">No changes.</p>
<? endif ?>
