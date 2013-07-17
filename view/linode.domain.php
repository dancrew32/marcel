<div class="media-body">
	<h5 class="media-heading">
		<? if ($status): ?>
			<a href="<?= $domain_url ?>" target="_blank"><?= $domain ?></a>
		<? else: ?>
			<span class="muted"><?= $domain ?></span>
		<? endif ?>
		<small>
			(<?= $id ?>)
		</small>

		<span class="label label-<?= $status_class ?> pull-right">
			<?= $status ? 'Up' : 'Down' ?>
		</span>
	<h5>
	<div class="media resources last">
		<?= r('linode', 'resources', ['domain_id' => $id]) ?>
	</div>
</div>
