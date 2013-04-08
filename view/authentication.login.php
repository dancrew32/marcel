<?= 
$form
->head('Fieldset')
->group('Group A', 
		$field_username,	
		$field_password
)->actions(
	new field('button', ['text' => 'Login'])
)	
?>
