<?
class controller_product_type extends controller_base {
	function __construct($o) {
		$this->root_path = app::get_path('Product Type Home');
		app::title('Product Types');
		parent::__construct($o);
   	}
 
	function all($o) {
		$page   = take($o['params'], 'page', 1); 
		$this->total = Product_Type::total();
		$rpp = 5;
		$this->pager = r('common', 'pager', [
			'total'  => $this->total,
			'rpp'    => $rpp,
			'page'   => $page,
			'base'   => "{$this->root_path}/",
		]);
		$this->product_types = Product_Type::find('all', [
			'limit'  => $rpp,
			'offset' => model::get_offset($page, $rpp),
			'order'  => 'id asc',
		]);
	}	

	function view($o) {
		$this->product_type = take($o, 'product_type');	
		$this->product_category = $this->product_type->category;
		$this->mode = take($o, 'mode', false);
	}	

	//function table($o) {
		//$this->product_types = take($o, 'product_types');	
	//}

	function add() {
		$product_type = new Product_Type;
		$product_type->name                = take($_POST, 'name');
		$product_type->slug                = take($_POST, 'slug');
		$product_type->product_category_id = take($_POST, 'category');
		$ok = $product_type->save();
		if ($ok) {
			note::set('product_type:add', $product_type->id);
			app::redir($this->root_path);
		}

		$product_type->to_note();
		app::redir($this->root_path);
	}	

	function edit($o) {
		$this->product_type = Product_Type::find_by_id(take($o['params'], 'id'));
		if (!$this->product_type) app::redir($this->root_path);
		if (!$this->is_post) return;

		$this->product_type->name                = take($_POST, 'name');
		$this->product_type->slug                = take($_POST, 'slug');
		$this->product_type->product_category_id = take($_POST, 'category');
		$ok = $this->product_type->save();
		if ($ok) {
			note::set('product_type:edit', $this->product_type->id);
			app::redir($this->root_path);
		}

		$this->product_type->to_note();
		app::redir("{$this->root_path}/edit/{$this->product_type->id}");
	}	

	function delete($o) {
		$id = take($o['params'], 'id');
		if (!$id) app::redir($this->root_path);

		$product_type = Product_Type::find_by_id($id);
		if (!$product_type) app::redir($this->root_path);

		$product_type->delete();
		note::set('product_type:delete', $product_type->id);
		app::redir($this->root_path);
	}	

/*
 * FORMS
 */
	# no view
	function add_form($o) {
		$product_type = new Product_Type;
		$product_type = $product_type->from_note();

		$this->form = new form;
		$this->form->open("{$this->root_path}/add", 'post', [
			'class' => 'last',
		]);
		$this->_build_form($product_type);
		$this->form->add(new field('submit_add'));

		echo $this->form;
	}

	# no view
	function edit_form($o) {
		$product_type = take($o, 'product_type');
		$product_type = $product_type->from_note();
		if (!$product_type) app::redir($this->root_path);

		$this->form = new form;
		$this->form->open("{$this->root_path}/edit/{$product_type->id}", 'post', [
			'class' => 'last',
		]);
		$this->_build_form($product_type);
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
			'placeholder'  => 'e.g. "Shoe Strings"',
			'value'        => take($o, 'name'),
		]);

		# Slug
		$slug_group = [ 'label' => 'Slug', 'class' => $o->error_class('slug') ]; 
		$slug_help  = new field('help', [ 'text' => $o->take_error('slug') ]);
		$slug_field = new field('input', [ 
			'name'         => 'slug', 
			'class'        => 'input-block-level required',
			'autocomplete' => false,
			'placeholder'  => 'e.g. "shoe-strings"',
			'value'        => take($o, 'slug'),
		]);

		# Category
		$category_group = [ 'label' => 'Category', 'class' => $o->error_class('product_category_id') ]; 
		$category_help  = new field('help', [ 'text' => $o->take_error('product_category_id') ]);
		$category_field = new field('select', [ 
			'name'         => 'category', 
			'class'        => 'input-block-level',
			'value'        => take($o, 'product_category_id'),
			'options'      => Product_Category::options(),
		]);

		 $this->form
		   ->group($name_group, $name_field, $name_help)
		   ->group($slug_group, $slug_field, $slug_help)
		   ->group($category_group, $category_field, $category_help)
		   ;
	}
}
