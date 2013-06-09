<?
class controller_product_type extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Product Type Home');
		auth::only(['product_type']);
		app::title('Product Types');
		parent::__construct($o);
   	}
 
	function all($o) {
		$page   = take($o['params'], 'page', 1); 
		$this->total = Product_Type::count();
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
		$this->products = $this->product_type->products;
		$this->mode = take($o, 'mode', false);
	}	

	function add() {
		$product_type = Product_Type::create($_POST);
		if ($product_type) {
			note::set('product_type:add', $product_type->id);
			app::redir($this->root_path);
		}

		$product_type->to_note();
		app::redir($this->root_path);
	}	

	function edit($o) {
		$this->product_type = Product_Type::find_by_id(take($o['params'], 'id'));
		if (!$this->product_type) app::redir($this->root_path);
		if (!POST) return;

		$ok = $this->product_type->update_attributes($_POST);
		if ($ok) {
			note::set('product_type:edit', $this->product_type->id);
			app::redir($this->root_path);
		}

		$this->product_type->to_note();
		app::redir(route::get('Product Type Edit', ['id' => $this->product_type->id]));
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
		$this->form->open(route::get('Product Type Add'), 'post', [
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
		$this->form->open(route::get('Product Type Edit', ['id' => $product_type->id]), 'post', [
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
		$category_options = Product_Category::options();
		if ($category_options) {
			$category_field = new field('select', [ 
				'name'         => 'product_category_id', 
				'class'        => 'input-block-level',
				'value'        => take($o, 'product_category_id'),
				'options'      => $category_options,
			]);
		} else {
			$category_field = new field('custom', [
				'text' => html::btn(route::get('Product Category Home'), 'Add a "Product Category"', 'plus'),
			]);
		}

		 $this->form
		   ->group($name_group, $name_field, $name_help)
		   ->group($slug_group, $slug_field, $slug_help)
		   ->group($category_group, $category_field, $category_help)
		   ;
	}
}
