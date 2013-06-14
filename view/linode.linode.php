<div class="media-body">
	<h4 class="media-heading">
		<?= $label ?>
		<small>
			(<?= $id ?>)
		</small>

		<span class="label label-<?= $status_class ?> pull-right">
			<?= $status ? 'Online' : 'Offline' ?>
		</span>
	<h4>

	<div class="media domains last">
		<?= r('linode', 'domains', ['linode_id' => $id]) ?>
	</div>
</div>
