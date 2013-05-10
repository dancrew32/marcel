<?
class controller_product_category extends controller_base {
	function __construct($o) {
		$this->root_path = app::get_path('Product Category Home');
		parent::__construct($o);
   	}
 
	function all($o) {
		$page   = take($o['params'], 'page', 1); 
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
		$this->total = Product_Category::total();
		$this->pager = r('common', 'pager', [
			'total'  => $this->total,
			'rpp'    => $rpp,
			'page'   => $page,
			'base'   => "{$this->root_path}/",
			'suffix' => h($format),
		]);
		$this->product_categories = Product_Category::find('all', [
			//'select' => 'id',
			'limit'  => $rpp,
			'offset' => model::get_offset($page, $rpp),
			'order'  => 'id asc',
		]);

		if ($format == '.json') 
			json(model::collection_to_json($this->product_categories));
	}	

	function view($o) {
		$this->product_category = take($o, 'product_category');	
		$this->types = $this->product_category->types;
		$this->type_count = count($this->types);
		$this->mode = take($o, 'mode', false);
	}	

	//function table($o) {
		//$this->product_categories = take($o, 'product_categories');	
	//}

	function add() {
		$product_category = new Product_Category;
		$product_category->name = take($_POST, 'name');
		$product_category->slug = take($_POST, 'slug');
		$ok = $product_category->save();
		if ($ok) {
			note::set('product_category:add', $product_category->id);
			app::redir($this->root_path);
		}

		$product_category->to_note();
		app::redir($this->root_path);
	}	

	function edit($o) {
		$this->product_category = Product_Category::find_by_id(take($o['params'], 'id'));
		if (!$this->product_category) app::redir($this->root_path);
		if (!$this->is_post) return;

		$this->product_category->name = take($_POST, 'name');
		$this->product_category->slug = take($_POST, 'slug');
		$ok = $this->product_category->save();
		if ($ok) {
			note::set('product_category:edit', $this->product_category->id);
			app::redir($this->root_path);
		}

		$this->product_category->to_note();
		app::redir("{$this->root_path}/edit/{$this->product_category->id}");
	}	

	function delete($o) {
		$id = take($o['params'], 'id');
		if (!$id) app::redir($this->root_path);

		$product_category = Product_Category::find_by_id($id);
		if (!$product_category) app::redir($this->root_path);

		$product_category->delete();
		note::set('product_category:delete', $product_category->id);
		app::redir($this->root_path);
	}	

/*
 * FORMS
 */
	# no view
	function add_form($o) {
		$product_category = new Product_Category;
		$product_category = $product_category->from_note();

		$this->form = new form;
		$this->form->open("{$this->root_path}/add", 'post', [
			'class' => 'last',
		]);
		$this->_build_form($product_category);
		$this->form->add(new field('submit_add'));

		echo $this->form;
	}

	# no view
	function edit_form($o) {
		$product_category = take($o, 'product_category');
		$product_category = $product_category->from_note();
		if (!$product_category) app::redir($this->root_path);

		$this->form = new form;
		$this->form->open("{$this->root_path}/edit/{$product_category->id}", 'post', [
			'class' => 'last',
		]);
		$this->_build_form($product_category);
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
			'placeholder'  => 'e.g. "Clothing"',
			'value'        => take($o, 'name'),
		]);

		# Slug
		$slug_group = [ 'label' => 'Slug', 'class' => $o->error_class('slug') ]; 
		$slug_help  = new field('help', [ 'text' => $o->take_error('slug') ]);
		$slug_field = new field('input', [ 
			'name'         => 'slug', 
			'class'        => 'input-block-level required',
			'autocomplete' => false,
			'placeholder'  => 'e.g. "clothing"',
			'value'        => take($o, 'slug'),
		]);

		 $this->form
		   ->group($name_group, $name_field, $name_help)
		   ->group($slug_group, $slug_field, $slug_help)
		   ;
	}
}
