<? foreach ($paths as $path): ?>
	<div class="well well-small alert-<?= $color_class ?>">
		<?= r('git', 'file', ['path' => $path, 'status' => $status]) ?>
	</div>
<? endforeach ?>
