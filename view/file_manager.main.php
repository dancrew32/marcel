<div class="row">
	<? if ($tree): ?>
		<div class="span8">
			<h1>File Manager</h1>
			<?= r('file_manager', 'search_form') ?>
			<?= r('file_manager', 'explorer', ['path' => $path]) ?>
		</div>
		<div class="span4">
			<?= r('file_manager', 'upload_form') ?>
		</div>
	<? else: ?>
		<div class="span12">
			<?= r('file_manager', 'search_form') ?>
			<?= r('file_manager', 'view', ['path' => $path]) ?>
		</div>
	<? endif ?>
</div>
