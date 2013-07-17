<? foreach ($paths as $path): ?>
	<?= r('git', 'file', [
		'path'        => $path,
		'status'      => $status,
		'color_class' => $color_class
	]) ?>
<? endforeach ?>
