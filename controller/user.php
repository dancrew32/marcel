<?
class controller_user extends controller_base {
	function __construct($o) {
		$this->root_path = app::get_path('User Home');
		auth::check('user_section');
		parent::__construct($o);
   	}
 
	function all($o) {
		$this->page = take($o['params'], 'page', 1); 
		$format = take($o['params'], 'format');
		switch ($format) {
			case '.table':
				$this->output_style = 'table';
				$rpp = 15;
				break;
			case '.json':
				$rpp = 10;
				break;
			default:	
				$this->output_style = 'media';
				$rpp = 5;
		}
		$this->total = User::total();
		$this->pager = r('common', 'pager', [
			'total'  => $this->total,
			'rpp'    => $rpp,
			'page'   => $this->page,
			'base'   => "{$this->root_path}/",
			'suffix' => h($format),
		]);
		$this->users = User::find('all', [
			'select' => 'id, first, last, email, username, active, last_login',
			'limit'  => $rpp,
			'offset' => model::get_offset($this->page, $rpp),
			'order'  => 'id asc',
		]);

		if ($format == '.json') 
			json(model::collection_to_json($this->users));
	}

	function view($o) {
		$this->user = take($o, 'user');	
		$this->mode = take($o, 'mode', false);
	}

	function table($o) {
		$this->users = take($o, 'users');	
	}

	function add($o) {
		$user = new User;
		$user->first    = take($_POST, 'first');
		$user->last     = take($_POST, 'last');
		$user->email    = take($_POST, 'email');
		$user->username = take($_POST, 'username');
		$user->password = take($_POST, 'password');
		$user->role     = take($_POST, 'role');
		$user->active   = take($_POST, 'active', 0);
		$ok = $user->save();
		if ($ok) {
			note::set('user:add', $user->id);
			app::redir($this->root_path);
		}

		$user->to_note();
		app::redir($this->root_path);
	}

	function edit($o) {
		$this->user = User::find_by_id(take($o['params'], 'id'));
		if (!$this->user) app::redir($this->root_path);
		if (!$this->is_post) return;

		$this->user->first    = take($_POST, 'first');
		$this->user->last     = take($_POST, 'last');
		$this->user->email    = take($_POST, 'email');
		$this->user->username = take($_POST, 'username');
		if (isset($_POST['password']{0}))
			$this->user->password = take($_POST, 'password');
		$this->user->role     = take($_POST, 'role');
		$this->user->active   = take($_POST, 'active', 0);

		$ok = $this->user->save();
		if ($ok) {
			note::set('user:edit', $this->user->id);
			app::redir($this->root_path);
		}

		$this->user->to_note();
		app::redir("{$this->root_path}/edit/{$this->user->id}");
	}

	function delete($o) {
		$id = take($o['params'], 'id');
		if (!$id) app::redir($this->root_path);

		$user = User::find_by_id($id);
		if (!$user) app::redir($this->root_path);

		$user->delete();
		note::set('user:delete', $user->id);
		app::redir($this->root_path);
	}

/*
 * FORMS
 */
	# no view
	function add_form() {
		$user = new User;
		$user = $user->from_note();

		$this->form = new form;
		$this->form->open("{$this->root_path}/add", 'post', [
			'class' => 'last',
			'id'    => 'user-add',
		]);
		$this->_build_form($user);
		$this->form->add(new field('submit_add'));
		echo $this->form;
	}

	# no view
	function edit_form($o) {
		$user = take($o, 'user');
		$user = $user->from_note();
		if (!$user) app::redir($this->root_path);

		$this->form = new form;
		$this->form->open("{$this->root_path}/edit/{$user->id}", 'post', [
			'class' => 'last', 
		]);
		$this->_build_form($user);
		$this->form->add(new field('submit_update'));
		echo $this->form;
	}

	private function _build_form($user) {
		app::asset('validate.min', 'js');
		# app::asset('view/user.form', 'js');


		# First name
		$first_name_group = [ 'label' => 'First Name', 'class' => $user->error_class('first') ];
		$first_name = new field('input', [ 
			'name'         => 'first', 
			'class'        => 'input-block-level required',
			'value'        => take($user, 'first'),
			'autocomplete' => false,
		]);
		$first_name_help = new field('help', [ 'text' => $user->take_error('first') ]);


		# Last Name
		$last_name_group = [ 'label' => 'Last Name', 'class' => $user->error_class('last') ];
		$last_name = new field('input', [ 
			'name'         => 'last', 
			'class'        => 'input-block-level required',
			'value'        => take($user, 'last'),
			'autocomplete' => false,
		]);
		$last_name_help = new field('help', [ 'text' => $user->take_error('last') ]);


		# Email 
		$email_group = [ 'label' => 'Email', 'class' => $user->error_class('email') ]; 
		$email = new field('email', [ 
			'name'         => 'email', 
			'class'        => 'input-block-level email required',
			'value'        => take($user, 'email'),
			'autocomplete' => false,
		]);
		$email_help = new field('help', [ 'text' => $user->take_error('email') ]);


		# Username
		$username_group = [ 'label' => 'Username', 'class' => $user->error_class('username') ]; 
		$username = new field('input', [ 
			'name'         => 'username', 
			'class'        => 'input-block-level required',
			'autocomplete' => false,
			'value'        => take($user, 'username'),
		]);
		$username_help = new field('help', [ 'text' => $user->take_error('username') ]);


		# Password
		$password_group = [ 'label' => 'Password', 'class' => $user->error_class('password') ];
		$password = new field('password', [ 
			'name'        => 'password', 
			'class'       => 'input-block-level',
			'autocomplete' => false,
		]);
		$password_help = new field('help', [ 'text' => $user->take_error('password') ]);


		# Role
		$role_group = [ 'label' => 'Role', 'class' => $user->error_class('role') ]; 
		$role = new field('select', [ 
			'name'        => 'role', 
			'class'       => 'input-block-level',
			'options'     => User::$roles,
			'value'       => take($user, 'role', 'user'),
		]);
		$role_help = new field('help', [ 'text' => $user->take_error('role') ]);


		# Active
		$active = new field('checkbox', [ 
			'name'    => 'active',
			'checked' => take($user, 'active'),
			'label'   => 'Activate',
			'inline'  => true,
		]);

		# Build Form
		$this->form
			->group($first_name_group, $first_name, $first_name_help)
			->group($last_name_group, $last_name, $last_name_help)
			->group($email_group, $email, $email_help)
			->group($username_group, $username, $username_help)
			->group($password_group, $password, $password_help)
			->group($role_group, $role, $role_help)
			->group($active);
	}
}
