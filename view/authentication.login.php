<?= 
$form
->head('Fieldset')
->group('Group A', 
		$field_username,	
		$field_password,
		$field_select,
		$field_multi_select,
		new field('email', ['type' => 'email', 'append' => 'you@test.com']),
		new field('email', ['type' => 'email', 'prepend' => '@'])
)->actions(
	new field('button', ['text' => 'Login'])
)	
?>
