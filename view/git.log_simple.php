<ul>
<? foreach ($commits as $commit): ?>
	<li>
		<?= r('git', 'log_simple_row', ['commit' => $commit]) ?>
	</li>
<? endforeach ?>
</ul>
