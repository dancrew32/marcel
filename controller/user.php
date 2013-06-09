<?
class controller_user extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('User Home');
		auth::only(['user']);
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
		$this->total = User::count();
		$this->pager = r('common', 'pager', [
			'total'  => $this->total,
			'rpp'    => $rpp,
			'page'   => $this->page,
			'base'   => "{$this->root_path}/",
			'suffix' => h($format),
		]);
		$this->users = User::find('all', [
			# Use select to avoid exposing passwords
			'select' => 'id, first, last, email, username, active, verified, last_login',
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
		$user = User::create($_POST);
		if ($user) {
			note::set('user:add', $user->id);
			app::redir($this->root_path);
		}

		$user->to_note();
		app::redir($this->root_path);
	}

	function edit($o) {
		$this->user = User::find_by_id(take($o['params'], 'id'));
		if (!$this->user) app::redir($this->root_path);
		if (!POST) return;

		# Don't change password if it's blank
		if (!isset($_POST['password']{0}))
			unset($_POST['password']);

		# handle booleans
		$_POST['verified'] = take_post('verified', 0);
		$_POST['active']   = take_post('active', 0);

		$ok = $this->user->update_attributes($_POST);
		if ($ok) {
			note::set('user:edit', $this->user->id);
			app::redir($this->root_path);
		}

		$this->user->to_note();
		app::redir(route::get('User Edit', ['id' => $this->user->id]));
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
 * EMAILS
 */
	function email_join($o) {
		$this->verification_url = take($o, 'verification_url');
	}

/*
 * FORMS
 */
	# no view
	function add_form() {
		$user = new User;
		$user = $user->from_note();

		$this->form = new form;
		$this->form->open(route::get('User Add'), 'post', [
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
		$this->form->open(route::get('User Edit', ['id' => $user->id]), 'post', [
			'class' => 'last', 
		]);
		$this->_build_form($user);
		$this->form->add(new field('submit_update'));
		echo $this->form;
	}

	private function _build_form($user) {

		# First name
		$first_name_group = [ 'label' => 'First Name', 'class' => $user->error_class('first') ];
		$first_name_help  = new field('help', [ 'text' => $user->take_error('first') ]);
		$first_name_field = new field('input', [ 
			'name'         => 'first', 
			'class'        => 'input-block-level',
			'value'        => take($user, 'first'),
			'autocomplete' => false,
		]);


		# Last Name
		$last_name_group = [ 'label' => 'Last Name', 'class' => $user->error_class('last') ];
		$last_name_help  = new field('help', [ 'text' => $user->take_error('last') ]);
		$last_name_field = new field('input', [ 
			'name'         => 'last', 
			'class'        => 'input-block-level',
			'value'        => take($user, 'last'),
			'autocomplete' => false,
		]);


		# Email 
		$email_group = [ 'label' => 'Email', 'class' => $user->error_class('email') ]; 
		$email_help  = new field('help', [ 'text' => $user->take_error('email') ]);
		$email_field = new field('email', [ 
			'name'         => 'email', 
			'class'        => 'input-block-level email required',
			'value'        => take($user, 'email'),
			'autocomplete' => false,
		]);


		# Username
		$username_group = [ 'label' => 'Username', 'class' => $user->error_class('username') ]; 
		$username_help  = new field('help', [ 'text' => $user->take_error('username') ]);
		$username_field = new field('input', [ 
			'name'         => 'username', 
			'class'        => 'input-block-level required',
			'autocomplete' => false,
			'value'        => take($user, 'username'),
		]);


		# Password
		$password_group = [ 'label' => 'Password', 'class' => $user->error_class('password') ];
		$password_help  = new field('help', [ 'text' => $user->take_error('password') ]);
		$password_field = new field('password', [ 
			'name'         => 'password', 
			'class'        => 'input-block-level',
			'autocomplete' => false,
		]);


		# User Type
		$user_type_group = [ 'label' => 'User Type', 'class' => $user->error_class('user_type_id') ]; 
		$user_type_help  = new field('help', [ 'text' => $user->take_error('user_type_id') ]);
		$user_type_field = new field('select', [ 
			'name'        => 'user_type_id', 
			'class'       => 'input-block-level',
			'options'     => User_Type::options(),
			'value'       => take($user, 'user_type_id') ? $user->user_type_id : User_Type::default_id(),
		]);


		# Active
		$active_field = new field('checkbox', [ 
			'name'    => 'active',
			'checked' => take($user, 'active'),
			'label'   => 'Activated',
			'inline'  => true,
		]);

		# Verified
		$verified_field = new field('checkbox', [ 
			'name'    => 'verified',
			'checked' => take($user, 'verified'),
			'label'   => 'Verified',
			'inline'  => true,
		]);

		# Build Form
		$this->form
			->group($first_name_group, $first_name_field, $first_name_help)
			->group($last_name_group, $last_name_field, $last_name_help)
			->group($email_group, $email_field, $email_help)
			->group($username_group, $username_field, $username_help)
			->group($password_group, $password_field, $password_help)
			->group($user_type_group, $user_type_field, $user_type_help)
			->group($active_field, $verified_field)
			;
	}
}
