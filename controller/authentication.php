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
		$this->field_username = new field('text', [
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

		if (!$this->is_post) return;

		$to = User::login($user, $pass);
		app::redir($to ? '/' : $action);
	}
}
