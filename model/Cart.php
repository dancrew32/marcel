<?
class Cart extends model {
	static $table_name = 'carts';

	static function get_type($key='cart') {
		if (User::$logged_in)
			$cart = Cart::find_by_user_id(User::$user->id);

		if (!$cart && cookie::exists($key)) {
			$hash = cookie::get($key);
			$cart = Cart::find_by_hash($hash);
		} 

		if (!isset($cart) || !$cart) {
			$hash = md5(SALT.time::now().SALT);

			$cart = new Cart;	
			$cart->hash = $hash;
			if (User::$logged_in)
				$cart->user_id = User::$user->id;
			else 
				cookie::set($key, $hash);	
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

}
