<?
class controller_product extends controller_base {
	function __construct($o) {
		$this->root_path = app::get_path('Product Home');
		auth::check('product_section');
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
		$this->total = Product::total();
		$this->pager = r('common', 'pager', [
			'total'  => $this->total,
			'rpp'    => $rpp,
			'page'   => $this->page,
			'base'   => "{$this->root_path}/",
			'suffix' => h($format),
		]);
		$this->products = Product::find('all', [
			//'select' => 'id',
			'limit'  => $rpp,
			'offset' => model::get_offset($this->page, $rpp),
			'order'  => 'id asc',
		]);

		if ($format == '.json') 
			json(model::collection_to_json($this->products));
	}	

	function view($o) {
		$this->product = take($o, 'product');	
		$this->mode = take($o, 'mode', false);
	}	

	function table($o) {
		$this->products = take($o, 'products');	
	}

	function add() {
		$product = new Product;
		$product->name        = take($_POST, 'name');
		$product->active      = take($_POST, 'active', 0);
		$product->price       = take($_POST, 'price', 0.00);
		$product->description = take($_POST, 'description');
		//$product->photo_ids   = take($_POST, 'photo_ids');
		$ok = $product->save();
		if ($ok) {
			note::set('product:add', $product->id);
			app::redir($this->root_path);
		}

		$product->to_note();
		app::redir($this->root_path);
	}	

	function edit($o) {
		$this->product = Product::find_by_id(take($o['params'], 'id'));
		if (!$this->product) app::redir($this->root_path);
		if (!$this->is_post) return;

		$this->product->name        = take($_POST, 'name');
		$this->product->active      = take($_POST, 'active', 0);
		$this->product->price       = take($_POST, 'price', 0.00);
		$this->product->description = take($_POST, 'description');
		//$product->photo_ids   = take($_POST, 'photo_ids');
		$ok = $this->product->save();
		if ($ok) {
			note::set('product:edit', $this->product->id);
			app::redir($this->root_path);
		}

		$this->product->to_note();
		app::redir("{$this->root_path}/edit/{$this->product->id}");
	}	

	function delete($o) {
		$id = take($o['params'], 'id');
		if (!$id) app::redir($this->root_path);

		$product = Product::find_by_id($id);
		if (!$product) app::redir($this->root_path);

		$product->delete();
		note::set('product:delete', $product->id);
		app::redir($this->root_path);
	}	

/*
 * FORMS
 */
	# no view
	function add_form($o) {
		$product = new Product;
		$product = $product->from_note();

		$this->form = new form;
		$this->form->open("{$this->root_path}/add", 'post', [
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
		if (!$product) app::redir($this->root_path);

		$this->form = new form;
		$this->form->open("{$this->root_path}/edit/{$product->id}", 'post', [
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
			'value'        => take($product, 'name'),
		]);

		# Price
		$price_group = [ 'label' => 'Price', 'class' => $product->error_class('price') ]; 
		$price_help  = new field('help', [ 'text' => $product->take_error('price') ]);
		$price_field = new field('input', [ 
			'name'         => 'price', 
			'class'        => 'input-block-level required',
			'autocomplete' => false,
			'value'        => number_format((float)take($product, 'price', 0), 2)
		]);

		# Description
		$description_group = [ 'label' => 'Description', 'class' => $product->error_class('description') ]; 
		$description_help  = new field('help', [ 'text' => $product->take_error('description') ]);
		$description_field = new field('textarea', [ 
			'name'         => 'description', 
			'class'        => 'input-block-level required',
			'autocomplete' => false,
			'value'        => take($product, 'description'),
		]);

		# Active
		$active_field = new field('checkbox', [ 
			'name'    => 'active',
			'checked' => take($product, 'active'),
			'label'   => 'Activate',
			'inline'  => true,
		]);


		$this->form
			->group($name_group, $name_help, $name_field)
			->group($price_group, $price_field, $price_help)
			->group($description_group, $description_field, $description_help)
			->group($active_field);
	}
}
