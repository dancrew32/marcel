<?
class controller_authentication extends controller_base {
	function logout() {
		User::logout();
	}

	function login($o) {
		$action = "/login";
		$user = take($_POST, 'user');
		$pass = take($_POST, 'pass');

		# Toggle help links
		$this->simple_mode = take($o, 'simple_mode', false);

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

	function join($o) {
		app::asset('validate.min', 'js');
		# app::asset('view/user.form', 'js');

		$this->form = new form;
		$this->form->open(app::get_path('Join'))
		->group([ 
				'label' => 'Email', 
				//'class' => model::error_class($errors, 'email'),
			], 
			new field('email', [ 
				'name'         => 'email', 
				'class'        => 'input-block-level email required',
				'autocomplete' => false,
			])
				//,
			//new field('help', [ 
				//'text' => model::take_error($errors, 'email'),
			//])
		)
		->group([ 
				'label'        => 'Password', 
				//'class'        => model::error_class($errors, 'password'),
			], 
			new field('password', [ 
				'name'        => 'password', 
				'class'       => 'input-block-level',
				'autocomplete' => false,
			])
				//,
			//new field('help', [ 
				//'text' => model::take_error($errors, 'password'),
			//])
		)
		->add(
			new field('submit', [
				'text' => 'Join',
			])
		);
	}

	function create_user($o) {
		$email = take($_POST, 'email');
		$pass  = take($_POST, 'password');

		$u = new User;
		$u->email = $email;
		$u->password = $u->spass($pass);
		$u->active = 1;
		$u->role = 'user';
		$ok = $u->save();
		if (!$ok)
			pd($u->errors->to_array());

		pd('created');
	}

}
