<table class="table table-bordered table-striped table-hover table-condensed">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Target</th>
			<th>Port</th>
			<th>Type</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($resources as $resource): ?>
			<?= r('linode', 'resource', ['resource' => $resource]) ?>
		<? endforeach ?>
	</tbody>
</table>
