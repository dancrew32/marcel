<?
class controller_product extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Product Home');
		parent::__construct($o);
   	}
 
	function all($o) {
		$this->page   = take($o, 'page', 1); 
		$format = take($o, 'format');
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
		$this->total = Product::count();
		$this->pager = r('common', 'pager', [
			'total'  => $this->total,
			'rpp'    => $rpp,
			'page'   => $this->page,
			'base'   => "{$this->root_path}/",
			'suffix' => h($format),
		]);
		$this->products = Product::find('all', [
			'limit'  => $rpp,
			'offset' => model::get_offset($this->page, $rpp),
			'order'  => 'id asc',
		]);

		if ($format == '.json') 
			json(model::collection_to_json($this->products));
	}	

	function view($o) {
		$this->product = take($o, 'product');	
		$this->product_type = $this->product->type;
		$this->product_category = $this->product_type ? $this->product_type->category : null;
		$this->mode = take($o, 'mode', false);
	}	

	function table($o) {
		$this->products = take($o, 'products');	
	}

	function sub_nav() {
		$this->items = [
			[
				'href' => route::get('Product Home'),
				'text' => "View All Products",
				'icon' => 'eye-open',
			],
			[
				'href' => route::get('Product Type Home'),
				'text' => "View All Product Types",
				'icon' => 'eye-open',
			],
			[
				'href' => route::get('Product Category Home'),
				'text' => "View All Product Categories",
				'icon' => 'eye-open',
			],
		];
   	}

	function add() {
		auth::only(['product']);
		$product = Product::create($_POST);
		if ($product) {
			note::set('product:add', $product->id);
			$this->redir();
		}

		$product->to_note();
		$this->redir();
	}	

	function edit($o) {
		auth::only(['product']);
		$this->product = Product::find_by_id(take($o, 'id'));
		if (!$this->product) $this->redir();
		if (!POST) return;

		# handle booleans
		$_POST['active'] = take_post('active', 0);

		$ok = $this->product->update_attributes($_POST);
		if ($ok) {
			note::set('product:edit', $this->product->id);
			$this->redir();
		}

		$this->product->to_note();
		app::redir(route::get('Product Edit', ['id' => $this->product->id]));
	}	

	function delete($o) {
		auth::only(['product']);
		$id = take($o, 'id');
		if (!$id) $this->redir();

		$product = Product::find_by_id($id);
		if (!$product) $this->redir();

		$product->delete();
		note::set('product:delete', $product->id);
		$this->redir();
	}	

/*
 * FORMS
 */
	# no view
	function add_form($o) {
		$product = new Product;
		$product = $product->from_note();

		$this->form = new form;
		$this->form->open(route::get('Product Add'), 'post', [
			'class' => 'last',
		]);
		$this->_build_form($product);
		$this->form->add(new field('submit_add'));

		echo $this->form;
	}

	# no view
	function edit_form($o) {
		$product = take($o, 'product');
		$product = $product->from_note();
		if (!$product) $this->redir();

		$this->form = new form;
		$this->form->open(route::get('Product Edit', ['id' => $product->id]), 'post', [
			'class' => 'last',
		]);
		$this->_build_form($product);
		$this->form->add(new field('submit_update'));

		echo $this->form;
	}

	private function _build_form($product) {

		# Name
		$name_group = [ 'label' => 'Name', 'class' => $product->error_class('name') ]; 
		$name_help  = new field('help', [ 'text' => $product->take_error('name') ]);
		$name_field = new field('input', [ 
			'name'         => 'name', 
			'class'        => 'input-block-level required',
			'autocomplete' => false,
			'placeholder'  => 'Enter product name',
			'value'        => take($product, 'name'),
		]);

		# Price
		$price_group = [ 'label' => 'Price', 'class' => $product->error_class('price') ]; 
		$price_help  = new field('help', [ 'text' => $product->take_error('price') ]);
		$price_field = new field('input', [ 
			'name'         => 'price', 
			'class'        => 'input-block-level required',
			'autocomplete' => false,
			'prepend'      => '$',
			'value'        => take($product, 'price'),
		]);

		# Description
		$description_group = [ 'label' => 'Description', 'class' => $product->error_class('description') ]; 
		$description_help  = new field('help', [ 'text' => $product->take_error('description') ]);
		$description_field = new field('textarea', [ 
			'name'         => 'description', 
			'class'        => 'input-block-level required',
			'autocomplete' => false,
			'placeholder'  => 'About your product',
			'value'        => take($product, 'description'),
		]);

		# Type
		$type_group = [ 'label' => 'Type', 'class' => $product->error_class('product_type_id') ]; 
		$type_help  = new field('help', [ 'text' => $product->take_error('product_type_id') ]);
		$type_options = Product_Type::options();
		if ($type_options) {
			$type_field = new field('select', [ 
				'name'    => 'product_type_id', 
				'class'   => 'input-block-level',
				'value'   => take($product, 'product_type_id'),
				'options' => $type_options,
			]);
		} else {
			$type_field = new field('custom', [
				'text' => html::btn(route::get('Product Type Home'), 'Add a "Product Type"', 'plus'),
			]);
		}

		# Active
		$active_field = new field('checkbox', [ 
			'name'    => 'active',
			'checked' => take($product, 'active'),
			'label'   => 'Activate',
			'inline'  => true,
		]);

		$this->form
			->group($name_group, $name_field, $name_help)
			->group($price_group, $price_field, $price_help)
			->group($description_group, $description_field, $description_help)
			->group($type_group, $type_field, $type_help)
			->group($active_field);
	}

}
