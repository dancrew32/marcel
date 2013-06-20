<? if (count($status['staged'])): ?>
	<h3>Staged</h3>
	<?= r('git', 'status_staged', ['paths' => $status['staged']]) ?>
<? endif ?>

<? if (count($status['untracked'])): ?>
	<h3>Untracked</h3>
	<?= r('git', 'status_untracked', ['paths' => $status['untracked']]) ?>
<? endif ?>

<? if (count($status['modified'])): ?>
	<h3>Modified</h3>
	<?= r('git', 'status_modified', ['paths' => $status['modified']]) ?>
<? endif ?>
