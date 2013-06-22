<div class="commit">
	<? if ($is_head || $after_head): ?>
		<a href="<?= $hash_url ?>" target="_blank" class="label label-<?= $label_class ?> pull-right">
			<?= $hash ?>
		</a>
	<? else: ?>
		<span class="label label-<?= $label_class ?> pull-right">
			<?= $hash ?>
		</span>
	<? endif ?>
	<span class="word-wrap">
	<?= $message ?>
	</span>
</div>
