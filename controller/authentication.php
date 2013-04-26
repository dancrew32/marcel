<?
class controller_authentication extends controller_base {
	function __construct($o) {
		$this->root_path = app::get_path('Login');
		parent::__construct($o);
   	}

	function logout() {
		User::logout();
	}

	function login($o) {
		$action = app::get_path('Login');
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
		$this->field_password = new field('password', [
			'name' => 'pass',
			'placeholder' => 'Password',
		]);

		if (!$this->is_post) return;

		$ok = User::login($user, $pass);
		app::redir($ok ? '/' : $action);
	}

	function join($o) {
		app::asset('validate.min', 'js');
		# app::asset('view/user.form', 'js');

		if (User::$logged_in)
			app::redir(app::get_path('Home'));
		
		$user = new User;
		$user = $user->from_note();

		$this->form = new form;
		$this->form->open(app::get_path('Join'))
		->group(
			[ 'label' => 'Email', 'class' => $user->error_class('email') ], 
			new field('email', [ 
				'name'         => 'email', 
				'class'        => 'input-block-level email required',
				'autocomplete' => false,
				'value'        => take($user, 'email'),
			]),
			new field('help', [ 'text' => $user->take_error('email') ])
		)
		->group(
			[ 'label' => 'Password', 'class' => $user->error_class('password') ], 
			new field('password', [ 
				'name'         => 'password', 
				'class'        => 'input-block-level',
				'autocomplete' => false,
			]),
			new field('help', [ 'text' => $user->take_error('password') ])
		)
		->add(
			new field('submit', [ 'text' => 'Join' ])
		);
	}

	function create_user($o) {
		$email = take($_POST, 'email');
		$pass  = take($_POST, 'password');

		$user = new User;
		$user->email = $email;
		if (isset($pass{0}))
			$user->password = $pass;
		$user->active = 1;
		$user->role = 'user';
		$ok = $user->save();
		if ($ok) {
			User::login($user->email, $pass);
			note::set('join:success', $user->id);
			app::redir(app::get_path('Home'));
		}

		$user->to_note();
		app::redir(app::get_path('Join'));
	}

}
