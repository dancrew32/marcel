<thead>
	<tr>
		<th class="hidden-phone">ID</th>
		<th>Name</th>
		<th>Slug</th>
		<th class="actions">Actions</th>
	</tr>
</thead>
<tbody>
<? foreach ($features as $f): ?>
	<tr>
		<td class="hidden-phone"><?= $f->id ?></td>
		<td><?= $f ?></td>
		<td><?= $f->slug ?></td>
		<td>
			<?= html::a("{$root_path}/edit/{$f->id}", 'Edit', 'edit');?>
		</td>
	</tr>
<? endforeach ?>
</tbody>
