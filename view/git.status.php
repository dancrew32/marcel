<? if (count($status['staged'])): ?>
	<h3>Staged</h3>
	<? foreach ($status['staged'] as $path): ?>
		<div class="well well-small alert-success">
			<?= r('git', 'file', ['path' => $path, 'status' => 'staged']) ?>
		</div>
	<? endforeach ?>
<? endif ?>

<? if (count($status['untracked'])): ?>
	<h3>Untracked</h3>
	<? foreach ($status['untracked'] as $path): ?>
		<div class="well well-small alert-important">
			<?= r('git', 'file', ['path' => $path, 'status' => 'untracked']) ?>
		</div>
	<? endforeach ?>
<? endif ?>

<? if (count($status['modified'])): ?>
	<h3>Modified</h3>
	<? foreach ($status['modified'] as $path): ?>
		<div class="well well-small alert-plain">
			<?= r('git', 'file', ['path' => $path, 'status' => 'modified']) ?>
		</div>
	<? endforeach ?>
<? endif ?>
