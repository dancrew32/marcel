<? 
$form
->add('Username', $field_username)
->add('Password', $field_password)
->br()
->add(new field('checkbox', ['label' => 'Remember Me', 'name' => 'remember', 'checked' => true, 'inline' => true]))
->actions(new field('submit', [
	'text' => 'Login', 
	'data-loading-text' => "Logging in...",
]));
if (!$simple_mode) {
$form->add(new field('help', [
	'text' => '
		<a href="#">
			Forgot Password?
		</a>
		or 
		<a href="'. app::get_path('Join') .'">
			Want to Join?
		</a>
	',
]));
}
echo $form;
