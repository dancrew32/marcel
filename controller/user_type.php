<?
class controller_user_type extends controller_base {
	function __construct($o) {
		$this->root_path = app::get_path('User Type Home');
		auth::check('user_type_section');
		parent::__construct($o);
   	}
 
	function all($o) {
		$page   = take($o['params'], 'page', 1); 
		$rpp    = 5;
		$this->total = User_Type::count();
		$this->pager = r('common', 'pager', [
			'total'  => $this->total,
			'rpp'    => $rpp,
			'page'   => $page,
			'base'   => "{$this->root_path}/",
		]);
		$this->user_types = User_Type::find('all', [
			'limit'  => $rpp,
			'offset' => model::get_offset($page, $rpp),
			'order'  => 'id asc',
		]);
	}	

	function view($o) {
		$this->user_type = take($o, 'user_type');	
		$this->mode = take($o, 'mode', false);
	}	

	function table($o) {
		$this->user_types = take($o, 'user_types');	
	}

	function add() {
		$user_type = new User_Type;
		$user_type->name = take($_POST, 'name');
		$user_type->slug = take($_POST, 'slug');
		$ok = $user_type->save();
		if ($ok) {
			note::set('user_type:add', $user_type->id);
			app::redir($this->root_path);
		}

		$user_type->to_note();
		app::redir($this->root_path);
	}	

	function edit($o) {
		$this->user_type = User_Type::find_by_id(take($o['params'], 'id'));
		if (!$this->user_type) app::redir($this->root_path);
		if (!$this->is_post) return;

		$this->user_type->name = take($_POST, 'name');
		$this->user_type->slug = take($_POST, 'slug');
		$ok = $this->user_type->save();
		if ($ok) {
			note::set('user_type:edit', $this->user_type->id);
			app::redir($this->root_path);
		}

		$this->user_type->to_note();
		app::redir("{$this->root_path}/edit/{$this->user_type->id}");
	}	

	function delete($o) {
		$id = take($o['params'], 'id');
		if (!$id) app::redir($this->root_path);

		$user_type = User_Type::find_by_id($id);
		if (!$user_type) app::redir($this->root_path);

		$user_type->delete();
		note::set('user_type:delete', $user_type->id);
		app::redir($this->root_path);
	}	

/*
 * FORMS
 */
	# no view
	function add_form($o) {
		$user_type = new User_Type;
		$user_type = $user_type->from_note();

		$this->form = new form;
		$this->form->open("{$this->root_path}/add", 'post', [
			'class' => 'last',
		]);
		$this->_build_form($user_type);
		$this->form->add(new field('submit_add'));

		echo $this->form;
	}

	# no view
	function edit_form($o) {
		$user_type = take($o, 'user_type');
		$user_type = $user_type->from_note();
		if (!$user_type) app::redir($this->root_path);

		$this->form = new form;
		$this->form->open("{$this->root_path}/edit/{$user_type->id}", 'post', [
			'class' => 'last',
		]);
		$this->_build_form($user_type);
		$this->form->add(new field('submit_update'));

		echo $this->form;
	}

	private function _build_form($o) {

		# Name
		$name_group = [ 'label' => 'Name', 'class' => $o->error_class('name') ]; 
		$name_help  = new field('help', [ 'text' => $o->take_error('name') ]);
		$name_field = new field('input', [ 
			'name'         => 'name', 
			'class'        => 'input-block-level required',
			'autocomplete' => false,
			'placeholder'  => 'e.g. "Power User"',
			'value'        => take($o, 'name'),
		]);

		# Slug
		$slug_group = [ 'label' => 'Slug', 'class' => $o->error_class('slug') ]; 
		$slug_help  = new field('help', [ 'text' => $o->take_error('slug') ]);
		$slug_field = new field('input', [ 
			'name'         => 'slug', 
			'class'        => 'input-block-level required',
			'autocomplete' => false,
			'placeholder'  => 'e.g. "power-user"',
			'value'        => take($o, 'slug'),
		]);


		 $this->form
		   ->group($name_group, $name_field, $name_help)
		   ->group($slug_group, $slug_field, $slug_help)
		   ;
	}
}
