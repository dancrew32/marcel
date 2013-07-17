<thead>
	<tr>
		<th class="hidden-phone">ID</th>
		<th>Name</th>
		<th>Email</th>
		<th class="hidden-phone">Last Login</th>
		<th class="hidden-phone">Status</th>
		<th class="actions">Actions</th>
	</tr>
</thead>
<tbody>
<? foreach ($users as $u): ?>
	<tr>
		<td class="hidden-phone"><?= $u->id ?></td>
		<td><?= $u->full_name() ?></td>
		<td><?= $u->email ?></td>
		<td class="hidden-phone"><?= time::ago($u->last_login) ?></td>
		<td class="hidden-phone"><?= $u->badge() ?></td>
		<td>
			<?= html::a(route::get('User Edit', ['id' => $u->id]));?>
		</td>
	</tr>
<? endforeach ?>
</tbody>
