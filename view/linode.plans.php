<table class="table table-bordered table-striped table-hover table-condensed">
	<thead>
		<tr>
			<th>ID</th>
			<th>Label</th>
			<th>Price</th>
			<th>RAM</th>
			<th>Disk</th>
			<th>Transfer</th>
		</tr>
	</thead>
		<? foreach ($plans as $plan): ?>
			<?= r('linode', 'plan', ['plan' => $plan]) ?>
		<? endforeach ?>
	</tbody>
</table>
