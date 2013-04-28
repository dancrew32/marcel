<?
class controller_authentication extends controller_base {
	function __construct($o) {
		$this->root_path = app::get_path('Login');
		parent::__construct($o);
   	}

	function main() {
		if (User::$logged_in) 
			app::redir(app::get_path('Home'));
		$this->version = app::$path == app::get_path('Join') ? 'join' : 'login';
   	}

	# no view
	function logout() {
		$ok = User::logout();
		note::set('logout:success', (int)$ok);
		app::redir(app::get_path('Home'));
	}

	# no view
	function login($o) {
		if (!auth::login()) app::redir(app::get_path('Home'));
		$action   = app::get_path('Login');
		$user     = take($_POST, 'user');
		$pass     = take($_POST, 'pass');
		$remember = take($_POST, 'remember', 0);

		# Toggle help links
		$this->simple_mode = take($o, 'simple_mode', false);

		# Form
		$this->form = new form;
		$this->form->open($action, 'post', [ 'class' => 'last' ]);
		$this->_build_login_form();
		$this->form->actions(
			new field('submit', [
				'text' => 'Login', 
				'icon' => 'user',
				'data-loading-text' => html::verb_icon('Logging in', 'user'),
			])
		);
		echo $this->form;
	}

	# no view
	function join($o) {
		if (!auth::join()) app::redir(app::get_path('Home'));
		
		$this->form = new form;
		$this->form->open(app::get_path('Join'), 'post', [ 'class' => 'last' ]);
		$this->_build_join_form();
		$this->form->actions(
			new field('submit', [
				'text' => 'Join', 
				'icon' => 'thumbs-up',
				'data-loading-text' => html::verb_icon('Joining', 'thumbs-up'),
			])
		);
		echo $this->form;
	}

	# no view
	function login_try($o) {
		$action = app::get_path('Login');
		$user = take($_POST, 'user');
		$pass = take($_POST, 'pass');
		$remember = take($_POST, 'remember', 0);

		$ok = User::login($user, $pass);
		if ($ok)
			note::set('login:success', 1);
		else {
			note::set('login:failure', [
				'user'     => $user,
				'remember' => $remember,
			], true);
		}
		app::redir($ok ? app::get_path('Home') : $action);
	}

	# no view
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
		note::set('join:failure', 1);
		app::redir(app::get_path('Login'));
	}


/*
 * FORMS
 */
	private function _build_login_form() {
		app::asset('validate.min', 'js');

		$note = note::get('login:failure', true);
		$error_class = $note ? 'error' : '';
		if ($note)
			$this->form->custom(
				html::alert('Invalid Username and/or Password', [ 'type' => 'error' ])
			);

		# Username
		$username_group = [ 'label' => 'Username or Email', 'class' => $error_class ]; 
		$username = new field('input', [ 
			'name'        => 'user',
			'id'          => 'login-user',
			'value'       => h(take($note, 'user')),
			'class'       => 'input-block-level required',
			'placeholder' => 'Username',
		]);

		# Password
		$password_group = [ 'label' => 'Password', 'class' => $error_class ]; 
		$password = new field('password', [ 
			'name'        => 'pass',
			'id'          => 'login-pass',
			'class'       => 'input-block-level required',
			'placeholder' => 'Password',
		]);

		# Remember Me
		$remember = new field('checkbox', [ 
			'name'    => 'remember',
			'checked' => take($note, 'remember', 1),
			'label'   => 'Remember Me',
			'inline'  => true,
		]);

		# Build Form
		$this->form
			->group($username_group, $username)
			->group($password_group, $password)
			->add($remember);
	}

	private function _build_join_form() {
		app::asset('validate.min', 'js');
		# app::asset('view/user.form', 'js');

		$user = new User;
		$user = $user->from_note();

		# Flash message
		$failure = note::get('join:failure');
		if ($failure) {
			$reasons = [];
			if ($user->errors) {
				foreach ((array) $user->errors as $e)
					foreach ($e as $v)
						$reasons[] = strtolower(" {$v}");
			}
			$reasons = count($reasons) ? (" because ". implode(",", $reasons)) : '';
			$this->form->custom(
				html::alert("Can't join{$reasons}.", [ 'type' => 'error' ])
			);
		}

		# Email
		$email_group = [ 'label' => 'Email', 'class' => $user->error_class('email') ];
		$email = new field('email', [ 
			'name'         => 'email', 
			'id'           => 'join-email',
			'class'        => 'input-block-level email required',
			'autocomplete' => false,
			'value'        => take($user, 'email'),
		]);
		$email_help = new field('help', [ 'text' => $user->take_error('email') ]);

		# Password
		$password_group = [ 'label' => 'Password', 'class' => $user->error_class('password') ];
		$password = new field('password', [ 
			'name'         => 'password', 
			'id'           => 'join-password',
			'class'        => 'input-block-level',
			'autocomplete' => false,
		]);
		$password_help = new field('help', [ 'text' => $user->take_error('password') ]);

		$this->form
			->group($email_group, $email, $email_help)
			->group($password_group, $password, $password_help);
	}
}
