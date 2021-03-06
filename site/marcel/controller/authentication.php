<?
class controller_authentication extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Login');
		parent::__construct($o);
   	}

	function main() {
		if (User::$logged_in) 
			app::redir(route::get('Home'));
		$this->version = route::$path == route::get('Join') ? 'join' : 'login';
		app::title($this->version == 'join' ? 'Join' : 'Login');
   	}

	# no view
	function logout() {
		$ok = User::logout();
		note::set('logout:success', (int)$ok);
		app::redir(route::get('Home'));
	}

	# no view
	function login($o) {
		$user     = take_post('user');
		$pass     = take_post('pass');
		$remember = take_post('remember', 0);

		# Toggle help links
		$this->simple_mode = take($o, 'simple_mode', false);

		# Form
		$this->form = new form;
		$this->form->open(route::get('Login'), 'post', [ 'class' => 'last' ]);
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
		$this->form = new form;
		$this->form->open(route::get('Join'), 'post', [ 'class' => 'last' ]);
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
		$user     = take_post('user');
		$pass     = take_post('pass');
		$remember = take_post('remember', 0);

		$ok = User::login($user, $pass);
		if ($ok) {
			note::set('login:success', 1);
			return app::redir(route::get('Home'));
		}
		note::set('login:failure', [
			'user'     => $user,
			'remember' => $remember,
		], true);
		app::redir(route::get('Login'));
	}

	# no view
	function create_user($o) {
		auth::can(['user']);
		$email = take_post('email');
		$pass  = take_post('password');

		$user = new User;
		$user->email = $email;
		if (isset($pass{0}))
			$user->password = $pass;
		$user->active = 1;
		$user->user_type_id = User_Type::find_by_slug('user')->id;
		$ok = $user->save();
		if ($ok) {
			User::login($user->email, $pass);
			$user->join_worker();

			note::set('join:success', $user->id);
			app::redir(route::get('Home'));
		}

		$user->to_note();
		note::set('join:failure', 1);
		app::redir(route::get('Login'));
	}

	function hybrid() {
		die('not yet');
		// TODO: link this shit up with API
		$config = array(
			"base_url" => "http://".config::$setting['base_url'],

			"providers" => array ( 
				"Twitter" => array ( 
					"enabled" => false,
					"keys"    => array ( "key" => "", "secret" => "" ) 
				),
			),

			// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
			"debug_mode" => false,

			"debug_file" => "",
		);
		require_once config::$setting['vendor_dir'] .'/hybridauth/hybridauth/Hybrid/Auth.php';
		try {
			$k = take($_GET, 'hauth.start');
			if ($k) {
				pd($k);	
			}
			$ha = new Hybrid_Auth($config);

			$li = $ha->authenticate('LinkedIn');
			pd($li);
			pd($_REQUEST);

		} catch (Exception $e) {

				pp($e);

		}
	}


/*
 * FORMS
 */
	private function _build_login_form() {
		$note = note::get('login:failure', true);
		$error_class = $note ? 'error' : '';
		if ($note)
			$this->form->custom(
				html::alert('Invalid Username and/or Password', [ 'type' => 'error' ])
			);

		# Username
		$username_group = [ 'label' => 'Username or Email', 'class' => $error_class ]; 
		$username_field = new field('input', [ 
			'name'        => 'user',
			'id'          => 'login-user',
			'value'       => h(take($note, 'user')),
			'class'       => 'input-block-level required',
			'placeholder' => 'Username',
		]);

		# Password
		$password_group = [ 'label' => 'Password', 'class' => $error_class ]; 
		$password_field = new field('password', [ 
			'name'        => 'pass',
			'id'          => 'login-pass',
			'class'       => 'input-block-level required',
			'placeholder' => 'Password',
		]);

		# Remember Me
		$remember_field = new field('checkbox', [ 
			'name'    => 'remember',
			'checked' => take($note, 'remember', 1),
			'label'   => 'Remember Me',
			'inline'  => true,
		]);

		# Build Form
		$this->form
			->group($username_group, $username_field)
			->group($password_group, $password_field)
			->add($remember_field);
	}

	private function _build_join_form() {
		$user = new User;
		$user = $user->from_note();

		# Flash message
		$failure = note::get('join:failure');
		if ($failure) {
			$reasons = util::list_english($user->errors);
			$this->form->custom(
				html::alert("Can't join because {$reasons}.", [ 'type' => 'error' ])
			);
		}

		# Email
		$email_group = [ 'label' => 'Email', 'class' => $user->error_class('email') ];
		$email_help = new field('help', [ 'text' => $user->take_error('email') ]);
		$email_field = new field('email', [ 
			'name'         => 'email', 
			'id'           => 'join-email',
			'class'        => 'input-block-level email required',
			'autocomplete' => false,
			'value'        => take($user, 'email'),
		]);

		# Password
		$password_group = [ 'label' => 'Password', 'class' => $user->error_class('password') ];
		$password_help  = new field('help', [ 'text' => $user->take_error('password') ]);
		$password_field = new field('password', [ 
			'name'         => 'password', 
			'id'           => 'join-password',
			'class'        => 'input-block-level',
			'autocomplete' => false,
		]);

		$this->form
			->group($email_group, $email_field, $email_help)
			->group($password_group, $password_field, $password_help);
	}
}
