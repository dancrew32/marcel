<td>
	<span class="word-wrap">
		<?= $message ?>
	</span>
</td>
<td>
<? if ($is_head || $after_head): ?>
	<a href="<?= $hash_url ?>" target="_blank" class="label label-full label-<?= $label_class ?>">
		<?= $hash ?>
	</a>
<? else: ?>
	<span class="label label-full label-<?= $label_class ?>">
		<?= $hash ?>
	</span>
<? endif ?>
</td>
