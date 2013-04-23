<thead>
	<tr>
	<? foreach ($keys as $key): ?>
		<th><?= $key ?></th>
	<? endforeach ?>
	</tr>
</thead>
<tbody>
<? foreach ($users as $user): ?>
	<tr>
		<? foreach ($user->to_array() as $k => $v): ?>
			<? switch ($k):
				case 'last_login': ?>
				<td><?= time::ago($v) ?></td>
			<? break ?>
			<? default: ?>
				<td><?= $v ?></td>
			<? endswitch ?>
		<? endforeach ?>
	</tr>
<? endforeach ?>
</tbody>
