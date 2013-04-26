<?
class controller_cart extends controller_base {

	const SHIPPING = 10.00;

	function __construct($o) {
		$this->root_path = app::get_path('Cart Home');
		parent::__construct($o);
   	}

	function index() {
		$this->items = [];

		$cart = Cart::get_type('cart:a');

		$items = $cart->get_items();
		$this->items = [];
		foreach ($items as $id) {
			if (isset($this->items[$id])) {
				$this->items[$id]->quantity++;	
				continue;
			}

			$product = Product::find_by_id($id);
			if (!$product) continue;

			$this->items[$id] = (object) [
				'id'       => $product->id,
				# ... more product attributes ...
				'quantity' => 1,
				'price'    => $product->price,
			];
		}

		$this->has_items = count($this->items) > 0;
		$this->shipping = self::SHIPPING;

		$this->grand_total = 0;
		foreach ($this->items as $i)
			$this->grand_total += number_format($i->price * $i->quantity, 2, '.', '');

		$this->grand_total += $this->shipping;
		$this->grand_total = number_format($this->grand_total, 2, '.', '');
	}

	function add($o) {
		$key = take($o['p'], 'key', false);
		if (!$key) _404();

		$cart = Cart::get_type('cart:a');
		$product = Product::find_by_id($key);
		if ($product && $product->price) {
			$cart->add_item($product->id);
			$saved = (bool) $cart->save();
		} else
			$saved = false;	

		if (util::is_ajax())
			json(['success' => $saved]);		

		note::set('cart:a:add', $product->id);
		app::redir($this->root_path);
	}


	function remove($o) {
		$key = take($o['p'], 'key', false);
		if (!$key) _404();

		$amount = take($o['p'], 'amount', 1);
		$cart = Cart::get_type('cart:a');
		if ($amount == '*') {
			$items = $cart->get_items();
			$data = [];
			foreach ($items as $item) {
				if ($item != $key)
					$data[] = $item;
			}
			$cart->data = json_encode($data);
		} else {
			$cart->remove_item($key, $amount);
		}
		$saved = (bool)$cart->save();

		if (util::is_ajax())
			json(['success' => $saved]);

		note::set('cart:a:remove', $cart->id);
		app::redir($this->root_path);
	}

	function success() {
		//$note = note::get('thank-you');
		//if (!$note) 
			//app::redir('/');

		//$note = json_decode($note);
		//$this->cardtype  = take($note, 'cardtype', false);
		//$this->cardlast4 = take($note, 'cardlast4', false);
		//$this->total     = take($note, 'total', false);
	}

	function quantity() {
		if (!$this->is_post || !util::is_ajax())
			json(['success' => false]);

		$id = take($_POST, 'id');
		$val = abs((int)take($_POST, 'val', 0));

		$product = Product::find_by_id($id);
		if (!$product)
			json(['success' => false]);

		$cart = Cart::get_type('cart:a');
		$items = $cart->get_items();

		$data = [];
		foreach ($items as $item) {
			if ($item != $id)
				$data[] = $item;
		}

		for ($i = 0; $i < $val; $i++)
			$data[] = $id;

		$total = number_format(number_format($product->price, 2, '.', '') * $val, 2, '.', '');

		$cart->data = json_encode($data);
		json([
			'success' => $cart->save(),
			'total'   => $total,
		]);
	}

	function checkout($o) {


		$name         = take($_POST, 'name', 'Anonymous');
		$email        = take($_POST, 'email', false);
		$address      = take($_POST, 'address', false);
		$stripe_token = take($_POST, 'stripe_token', false);
		if (!$email || !$address || !$stripe_token)
			app::redir($this->root_path);

		$cart = Cart::get_type('cart:a');
		if (!$cart || $cart->active)
			app::redir($this->root_path);

		$items = $cart->get_items();
		$this->items = [];
		foreach ($items as $id) {
			if (isset($this->items[$id])) {
				$this->items[$id]->quantity++;	
				continue;
			}

			$product = Product::find_by_id($id);
			if (!$product) continue;

			$this->items[$id] = (object) [
				'id'       => $product->id,
				'quantity' => 1,
				'price'    => $product->price,
			];
		}

		$total = 0;
		foreach ($this->items as $item)
			$total += ($item->price * $item->quantity);

		// discounts/tax/shipping
		if ($total > 0)
			$total += self::SHIPPING;

		$total = number_format($total, 2, '.', ''); # to cents
		$total_cents = $total * 100;

		$charge = credit::charge([
			'card'        => $stripe_token,
			'amount'      => $total_cents,
			'description' => $email,
		]);
		$card = $charge->card;

		// TODO: log transaction
		//$t = new Transaction;
		//$transaction_data = [
			//'name'        => take($_POST, 'name', 'Anonymous'),
			//'email'       => $email,
			//'address'     => $address,
			//'mode'        => ENV == 'DEV' ? 'TEST' : 'LIVE',
			//'cardtype'    => $card->type,
			//'cardlast4'   => $card->last4,
			//'token'       => $stripe_token,
			//'total'       => $total,
			//'total_cents' => $total_cents,
			//'paid'        => false,
		//];

		# OKAY
		if ($charge->paid) {
			$cart->active = true;
			$cart->save();

			// TODO: finish transaction log
			//$transaction_data['paid'] = true;
			//$t->data = json_encode($transaction_data);
			//$t->save();
			
			# Delete cart
			cookie::delete('cart:a');

			//note::set('thank-you', json_encode([
				//'cardtype'  => $card->type,
				//'cardlast4' => $card->last4,
				//'total'     => "\${$total}",
			//]));

			//$mail = new mail;
			//$mail->From  = 'admin@site.com';
			//$mail->FromName = SITE_NAME;
			//$mail->AddAddress($email, 'Example User');
			//$mail->Subject  = "Thanks for your purchase!";
			//$mail->Body     = "Thanks for your purchase of X. \${$total} was charged to your {$card->type}!.";
			//$mail->Queue();

			app::redir(app::get_path('Checkout Success'));

		} 

		# FAILED
		else {
			$cart->active = false;
			$cart->save();

			// TODO: transaction data
			//$t->data = json_encode($transaction_data);
			//$t->save();
			die("Something went wrong. Don't worry, you were not charged.");	
		}
	}


/*
 * FORM
 */
	private function _build_form() {
		app::asset('//js.stripe.com/v1', 'js');
	}

}
