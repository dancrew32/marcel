<thead>
	<tr>
		<th class="hidden-phone">ID</th>
		<th>Name</th>
		<th class="actions">Actions</th>
	</tr>
</thead>
<tbody>
<? foreach ($products as $p): ?>
	<tr>
		<td class="hidden-phone"><?= $p->id ?></td>
		<td><?= $p->name ?></td>
		<td>
			<?= html::a("{$root_path}/edit/{$p->id}", 'Edit', 'edit');?>
		</td>
	</tr>
<? endforeach ?>
</tbody>
