<table class="table">
	<thead>
		<tr>
			<th>Commit Message</th>
			<th>Hash</th>
		</tr>
	</thead>
	<tbody>
	<? foreach ($commits as $commit): ?>
		<tr>
			<?= r('git', 'log_simple_row', ['commit' => $commit, 'after_head' => $after_head]) ?>
		</tr>
		<? if (!$after_head) $after_head = take($commit, 'is_head'); ?>
	<? endforeach ?>
	</tbody>
</table>
