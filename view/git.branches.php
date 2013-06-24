<table class="table table-condensed">
	<thead>
		<tr>
			<th>Branch Name</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($branches as $branch): ?>
			<tr<? echoif($branch == $current_branch, ' class="success"') ?>>
				<?= r('git', 'branch', ['branch' => $branch, 'current_branch' => $current_branch]) ?>
			</tr>
		<? endforeach ?>
	</tbody>
</table>
