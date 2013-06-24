<td>
	<?= html::a($branch_url, $branch) ?>
	<? echoif($is_current, '<small class="success">(current)</small>') ?>
</td>
<td class="actions">
	<? if ($delete_url): ?>
		<?= html::btn($delete_url, "Delete <b>{$branch}</b>", 'remove-circle', 'mini pull-right') ?>
	<? endif ?>
	<? if (!$is_current): ?>
		<?= html::btn($checkout_url, 'Checkout', 'plus', 'mini pull-right') ?>
	<? endif ?>
</td>
