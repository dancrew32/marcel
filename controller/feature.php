<?
class controller_feature extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Feature Home');
		auth::only(['feature']);
		parent::__construct($o);
   	}
 
	function all($o) {
		$this->page   = take($o['params'], 'page', 1); 
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
		$this->total = Feature::count();
		$this->pager = r('common', 'pager', [
			'total'  => $this->total,
			'rpp'    => $rpp,
			'page'   => $this->page,
			'base'   => "{$this->root_path}/",
			'suffix' => h($format),
		]);
		$this->features = Feature::find('all', [
			//'select' => 'id',
			'limit'  => $rpp,
			'offset' => model::get_offset($this->page, $rpp),
			'order'  => 'id asc',
		]);

		if ($format == '.json') 
			json(model::collection_to_json($this->features));
	}	

	function view($o) {
		$this->feature = take($o, 'feature');	
		$this->mode = take($o, 'mode', false);
	}	

	function table($o) {
		$this->features = take($o, 'features');	
	}

	function add() {
		$feature = Feature::create($_POST);
		if ($feature) {
			note::set('feature:add', $feature->id);
			app::redir($this->root_path);
		}

		$feature->to_note();
		app::redir($this->root_path);
	}	

	function edit($o) {
		$this->feature = Feature::find_by_id(take($o['params'], 'id'));
		if (!$this->feature) app::redir($this->root_path);
		if (!POST) return;

		$ok = $this->feature->update_attributes($_POST);
		if ($ok) {
			note::set('feature:edit', $this->feature->id);
			app::redir($this->root_path);
		}

		$this->feature->to_note();
		app::redir("{$this->root_path}/edit/{$this->feature->id}");
	}	

	function delete($o) {
		$id = take($o['params'], 'id');
		if (!$id) app::redir($this->root_path);

		$feature = Feature::find_by_id($id);
		if (!$feature) app::redir($this->root_path);

		$feature->delete();
		note::set('feature:delete', $feature->id);
		app::redir($this->root_path);
	}	

/*
 * FORMS
 */
	# no view
	function add_form($o) {
		$feature = new Feature;
		$feature = $feature->from_note();

		$this->form = new form;
		$this->form->open("{$this->root_path}/add", 'post', [
			'class' => 'last',
		]);
		$this->_build_form($feature);
		$this->form->add(new field('submit_add'));

		echo $this->form;
	}

	# no view
	function edit_form($o) {
		$feature = take($o, 'feature');
		$feature = $feature->from_note();
		if (!$feature) app::redir($this->root_path);

		$this->form = new form;
		$this->form->open("{$this->root_path}/edit/{$feature->id}", 'post', [
			'class' => 'last',
		]);
		$this->_build_form($feature);
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
			'placeholder'  => 'e.g. "Worker"',
			'value'        => take($o, 'name'),
		]);

		# Slug
		$slug_group = [ 'label' => 'Slug', 'class' => $o->error_class('slug') ]; 
		$slug_help  = new field('help', [ 'text' => $o->take_error('slug') ]);
		$slug_field = new field('input', [ 
			'name'         => 'slug', 
			'class'        => 'input-block-level required',
			'autocomplete' => false,
			'placeholder'  => 'e.g. "worker"',
			'value'        => take($o, 'slug'),
		]);


		 $this->form
		   ->group($name_group, $name_field, $name_help)
		   ->group($slug_group, $slug_field, $slug_help)
		   ;

	}
}
