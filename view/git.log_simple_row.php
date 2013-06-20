<div>
	<? if ($is_head || $after_head): ?>
		<a href="<?= $hash_url ?>" class="label label-<?= $label_class ?> pull-right">
			<?= $hash ?>
		</a>
	<? else: ?>
		<span class="label label-<?= $label_class ?> pull-right">
			<?= $hash ?>
		</span>
	<? endif ?>
	<?= $message ?>
</div>
