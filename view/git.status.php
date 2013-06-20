<? if ($staged): ?>
	<h3>Staged</h3>
	<?= r('git', 'status_staged', ['paths' => $staged]) ?>
<? endif ?>

<? if ($modified): ?>
	<h3>Modified</h3>
	<?= r('git', 'status_modified', ['paths' => $modified]) ?>
<? endif ?>

<? if ($untracked): ?>
	<h3>Untracked</h3>
	<?= r('git', 'status_untracked', ['paths' => $untracked]) ?>
<? endif ?>

