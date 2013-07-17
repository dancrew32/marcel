<table class="table table-condensed">
	<thead>
		<tr>
			<th>Submodule</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<? foreach ($submodules as $path => $url): ?>
		<tr>
			<?= r('git', 'submodule', ['path' => $path, 'url' => $url]) ?>
		</tr>
<? endforeach ?>
	</tbody>
</table>
