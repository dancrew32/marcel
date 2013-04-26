<?
class Cart extends model {
	static $table_name = 'carts';

	static function get_type($key='cart') {

		# TODO: if User::$logged_in, use $_SESSION cart

		if (cookie::exists($key)) {
			$hash = cookie::get($key);
			$cart = Cart::find_by_hash($hash);
		} 

		if (!isset($cart) || !$cart) {
			$hash = md5(SALT.time::now().SALT);

			cookie::set($key, $hash);	
			$cart = new Cart;	
			$cart->hash = $hash;
			if (User::$logged_in)
				$cart->user_id = User::$user->id;
		}

		return $cart;
	}

	function get_items() {
		$data = isset($this->data{0}) ? json_decode($this->data) : [];
		return (array) $data;
	}

	function add_item($key) {
		$data = $this->get_items();
		$data[] = $key;
		$this->data = json_encode($data);
	}

	function remove_item($key, $amount=1) {
		$amount = abs($amount);
		$data = $this->get_items();
		for ($i = 0; $i < $amount; $i++) {
			$index = array_search($key, $data);
			if ($index === false) break;
			array_splice($data, $index, 1);
		}
		$this->data = json_encode((array)$data);
	}

	/*
	# Relationships
	static $has_one = [
		[ 'stats', 'class_name' => 'Cat_Stat' ]	
		[ 'foos', 'through' => 'bars' ]	
	];
	static $has_many = [
		[ 'cats' ],
		[ 'foos', 'through' => 'bars' ],
	];
	static $belongs_to = [
		[ 'cheeses' ],
	];

	# Validation
	static $validates_presence_of = [
		['name', 'message' => 'must be added!'],
	];
	static $validates_size_of = [
		['fieldz', 'is' => 42, 'message' => 'must be exactly 42 chars'],
		['fielda', 'minimum' => 9, 'too_short' => 'must be at least 9 characters long'],
		['fieldb', 'maximum' => 20, 'too_long' => 'is too long!'],
		['fieldc', 'within' => [5-10], 
			'too_short' => 'must be longer than 5 (less than 10)', 
			'too_long' => 'must be less than 10 (greater than 5 though)!'
		],
	];
	static $validates_inclusion_of = [
		['types', 'in' => ['list', 'of', 'allowed', 'types'], 'message' => 'is invalid'],
	];
	static $validates_exclusion_of = [
		['password', 'in' => ['list', 'of', 'bad', 'passwords'], 'message' => 'is invalid'],
	];
	static $validates_numericality_of = [
		['price', 'greater_than' => 0.01],
		['quantity', 'only_integer' => true],
		['shipping', 'greater_than_or_equal_to' => 0],
		['discount', 'less_than_or_equal_to' => 5, 'greater_than_or_equal_to' => 0],
	];
	static $validates_uniqueness_of = [
		['email', 'message' => 'Sorry that email is taken'],
	];
	static $validates_format_of = [
		['email', 'with' =>
		'/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/'],
		['password', 'with' =>
			'/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/', 'message' => 'is too weak'],
	];

	# Callbacks 
	# (array of local function names)
	static $before_save = [];                 # called before a model is saved
	static $before_create = [];               # called before a NEW model is to be inserted into the database
	static $before_update = [];               # called before an existing model has been saved
	static $before_validation = [];           # called before running validators
	static $before_validation_on_create = []; # called before validation on a NEW model being inserted
	static $before_validation_on_update = []; # same as above except for an existing model being saved
	static $before_destroy = [];              # called after a model has been deleted
	static $before_save = [];                 # called after a model is saved
	static $after_save = [];                  # called after a model is saved
	static $after_create = [];                # called after a NEW model has been inserted into the database
	static $after_update = [];                # called after an existing model has been saved
	static $after_validation = [];            # called after running validators
	static $after_validation_on_create = [];  # called after validation on a NEW model being inserted
	static $after_validation_on_update = [];  # same as above except for an existing model being saved
	static $after_destroy = [];               # called after a model has been deleted
	*/

}
