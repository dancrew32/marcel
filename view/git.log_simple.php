<ul>
<? foreach ($commits as $commit): ?>
	<li>
		<?= r('git', 'log_simple_row', ['commit' => $commit, 'after_head' => $after_head]) ?>
	</li>
	<? if (!$after_head) $after_head = take($commit, 'is_head'); ?>
<? endforeach ?>
</ul>
