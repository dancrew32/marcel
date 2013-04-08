<?
class controller_authentication extends controller_base {
	function logout() {
		User::logout();
	}

	function login() {
		$action = "/login";
		$user = take($_POST, 'user');
		$pass = take($_POST, 'pass');

		# Form
		$this->form = new form;
		$this->form->open($action);

		# Username
		$this->field_username = new field('input', [
			'type' => 'text',
			'name' => 'user',
			'value' => $user,
			'placeholder' => 'Username',
		]);

		# Password
		$this->field_password = new field('input', [
			'type' => 'password',
			'name' => 'pass',
			'placeholder' => 'Password',
		]);

		# Select
		$this->field_select = new field('select', [
			'name' => 'foo', 
			'value' => 'b',
			'options' => [
				'a' => 'A',
				'b' => 'B', 
				'c' => 'C' 
			]
		]);

		# Multi Select
		$this->field_multi_select = new field('select', [
			'name' => 'foo', 
			'value' => ['b', 'c'], 
			'multiple' => 'multiple', 
			'options' => [
				'a' => 'A', 
				'b' => 'B', 
				'c' => 'C',
			]
		]);

		if (!$this->is_post) return;

		$to = User::login($user, $pass);
		app::redir($to ? '/' : $action);
	}
}
