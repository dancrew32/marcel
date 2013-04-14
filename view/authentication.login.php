<? 
$form
->add('Username', $field_username)
->add('Password', $field_password)
->br()
->add(new field('checkbox', ['label' => 'Remember Me', 'name' => 'remember', 'checked' => true, 'inline' => true]))
->actions(new field('button', ['text' => 'Login', 'data-loading-text' => "Logging in..."]));
if (!$simple_mode) {
$form->custom('
<div class="help" style="display: inline-block; vertical-align: middle; margin:5px 5px 0 5px;">
	<a href="#">
		Forgot Password?
	</a>
	or 
	<a href="#">
		Want to Join?
	</a>
</div>
');
}
echo $form;
?>
