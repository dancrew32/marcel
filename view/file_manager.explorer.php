<ul class="nav">
<? foreach ($files as $file_path): ?>
	<li>
		<?= r('file_manager', 'explorer_file', ['file_path' => $file_path]) ?>
	</li>
<? endforeach ?>
</ul>
