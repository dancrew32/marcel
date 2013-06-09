<?
class controller_cart extends controller_base {

	const SHIPPING = 10.00;

	function __construct($o) {
		$this->root_path = route::get('Cart Home');
		auth::only(['cart']);
		parent::__construct($o);
   	}

	function main() {

		# Arbitrary test product
		//$first_product = Product::first();
		//$this->test_product_id = take($first_product, 'id', 1);

		$this->items = [];
		$cart = Cart::get_type(Cart::MAIN);

		$items = $cart->get_items();
		$this->items = [];
		$this->total_items = 0;
		foreach ($items as $id) {
			if (isset($this->items[$id])) {
				$this->items[$id]->quantity++;	
				$this->total_items++;
				continue;
			}

			$product = Product::find_by_id($id);
			if (!$product) continue;

			$this->items[$id] = (object) [
				'id'       => $product->id,
				'product'  => $product,
				'quantity' => 1,
			];
			$this->total_items++;
		}

		$this->has_items = count($this->items) > 0;
		$this->shipping  = self::SHIPPING;

		$this->grand_total = 0;
		foreach ($this->items as $i)
			$this->grand_total += number_format($i->product->price * $i->quantity, 2, '.', '');

		$this->grand_total += $this->shipping;
		$this->grand_total = number_format($this->grand_total, 2, '.', '');
	}

	function view($o) {
		$this->items = take($o, 'items');
		$this->items_count = count($this->items);

		$this->shipping  = self::SHIPPING;

		$this->grand_total = 0;
		foreach ($this->items as $i)
			$this->grand_total += number_format($i->product->price * $i->quantity, 2, '.', '');

		$this->grand_total += $this->shipping;
		$this->grand_total = number_format($this->grand_total, 2, '.', '');
	}

	function add($o) {
		$key = take($o['params'], 'key', false);
		if (!$key) _404();

		$amount = take($o['params'], 'amount', 1);

		$cart = Cart::get_type(Cart::MAIN);
		$product = Product::find_by_id($key);
		$saved = $product && $product->price;
		if ($saved) {
			$cart->add_item($product->id, $amount);
			$saved = (bool) $cart->save();
		}

		if (AJAX)
			json(['success' => $saved]);		

		note::set(Cart::MAIN.':add', $product->id);
		app::redir($this->root_path);
	}


	function remove($o) {
		$key = take($o['params'], 'key', false);
		if (!$key) _404();

		$amount = take($o['params'], 'amount', 1);
		$cart = Cart::get_type(Cart::MAIN);
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

		if (AJAX)
			json(['success' => $saved]);

		note::set(Cart::MAIN.':remove', $cart->id);
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
		if (!POST || !AJAX)
			json(['success' => false]);

		$id = take($_POST, 'id');
		$val = abs((int)take($_POST, 'val', 0));

		$product = Product::find_by_id($id);
		if (!$product)
			json(['success' => false]);

		$cart = Cart::get_type(Cart::MAIN);
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
		if (User::$logged_in) {
			$name  = ifset(User::$user->full_name(), 'Anonymous');
			$email = User::$user->email;
		} else {
			$name  = take($_POST, 'name', 'Anonymous');
			$email = take($_POST, 'email', false);
		}
		$address = take($_POST, 'address', false);

		$stripe_token = take($_POST, 'stripe_token', false);

		pd(get_defined_vars());
		if (!$email || !$address || !$stripe_token)
			app::redir($this->root_path);

		$cart = Cart::get_type(Cart::MAIN);
		if (!$cart || $cart->complete)
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
			# TODO: prevent purchase of inactive products

			$this->items[$id] = (object) [
				'id'       => $product->id,
				'quantity' => 1,
				'price'    => $product->price,
			];
		}

		$total = 0;
		foreach ($this->items as $item)
			$total += ($item->price * $item->quantity);

		# discounts/tax/shipping
		if ($total > 0)
			$total += self::SHIPPING;

		$total = number_format($total, 2, '.', ''); # to cents
		$total_cents = $total * 100;

		pd(get_defined_vars());
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
			$cart->complete = true;
			$cart->save();

			// TODO: finish transaction log
			//$transaction_data['paid'] = true;
			//$t->data = json_encode($transaction_data);
			//$t->save();
			
			# Delete cart
			cookie::delete(Cart::MAIN);

			//note::set('thank-you', json_encode([
				//'cardtype'  => $card->type,
				//'cardlast4' => $card->last4,
				//'total'     => "\${$total}",
			//]));

			//$mail = new mail;
			//$mail->from  = 'admin@site.com';
			//$mail->from_name = APP_NAME;
			//$mail->add_address($email, 'Example User');
			//$mail->subject  = "Thanks for your purchase!";
			//$mail->body     = "Thanks for your purchase of X. \${$total} was charged to your {$card->type}!.";
			//$mail->queue();

			app::redir(route::get('Checkout Success'));

		} 

		# FAILED
		else {
			$cart->complete = false;
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
	function checkout_form() {
		$cart = new Cart;
		$cart = $cart->from_note();

		$this->form = new form;
		$this->form->open("{$this->root_path}/checkout", 'post', [
			'class' => 'last',
			'id'    => 'checkout-cart',
		]);
		$this->_build_form($cart);
		$this->form->custom(html::alert('<div class="payment-errors"></div>', [
			'type'   => 'error',
			'hidden' => true
		]));
		$this->form->add(new field('submit', [
			'text' => 'Buy Now',
			'icon' => 'gift',
			'data-loading-text' => html::verb_icon('Buying', 'gift'),
		]));
		echo $this->form;
	}

	private function _build_form($cart) {
		app::asset('//js.stripe.com/v1', 'js');
		app::asset('view/cart.checkout_form', 'js');

		# Name (TODO: skip if logged in)
		$name_group = [ 'label' => "Recipient's Name", 'class' => $cart->error_class('name') ];
		$name_help  = new field('help', [ 'text' => $cart->take_error('name') ]);
		$name_field = new field('input', [
			'name'        => 'name',
			'id'          => 'cart-name',
			'class'       => 'input-block-level',
			'value'       => User::$logged_in ? User::$user->full_name() : take($cart, 'name'),
			'disabled'    => User::$logged_in,
			'placeholder' => "Who we'll ship to.",
		]);

		# Email (TODO: skip if logged in)
		$email_group = [ 'label' => "Your Email", 'class' => $cart->error_class('email') ];
		$email_help  = new field('help', [ 'text' => $cart->take_error('email') ]);
		$email_field = new field('input', [
			'name'        => 'email',
			'id'          => 'cart-email',
			'class'       => 'input-block-level required',
			'disabled'    => User::$logged_in,
			'value'       => User::$logged_in ? User::$user->email : take($cart, 'email'),
			'placeholder' => "For purchase confirmation only.",
		]);

		# Shipping Address (TODO: skip if logged in and address exists)
		$address_group = [ 'label' => "Shipping Address", 'class' => $cart->error_class('email') ];
		$address_help  = new field('help', [ 'text' => $cart->take_error('email') ]);
		$address_field = new field('typeahead', [
			'name'           => 'address',
			'class'          => 'input-block-level required',
			'id'             => 'cart-address',
			'value'          => take($cart, 'address'),
			'placeholder'    => 'E.g. "123 Anywhere St., San Francisco, CA 94121"',
			'data-api'       => route::get('Geocode'),
			'data-items'     => 5,
			'data-minLength' => 20,
		]);

		# Card Number
		$card_group = [ 'label' => "Card Number", 'class' => $cart->error_class('card') ];
		$card_help  = new field('help', [ 'text' => $cart->take_error('card') ]);
		$card_field = new field('tel', [ # tel feels better for creditcard numbers
			'name'         => 'card',
			'class'        => 'input-block-level required',
			'maxlength'    => 16,
			'min'          => 16,
			'id'           => 'cart-card',
			'value'        => take($cart, 'card'),
			'placeholder'  => 'E.g. "44443332221111"',
			'data-stripe'  => 'number',
			'autocomplete' => false,
		]);

		# Card Number
		$cvc_group = [ 'label' => "CVC", 'class' => $cart->error_class('cvc') ];
		$cvc_help  = new field('help', [ 'text' => $cart->take_error('cvc') ]);
		$cvc_field = new field('tel', [ # tel feels better for cvc
			'name'         => 'cvc',
			'class'        => 'input-block-level required',
			'maxlength'    => 4,
			'minlength'    => 3,
			'id'           => 'cart-cvc',
			'value'        => take($cart, 'cvc'),
			'placeholder'  => 'The 3 to 4 digit code on the back.',
			'data-stripe'  => 'cvc',
			'autocomplete' => false,
		]);

		$exp_month_group = [ 'label' => "Expiration Month", 'class' => $cart->error_class('exp_month') ];
		$exp_month_help  = new field('help', [ 'text' => $cart->take_error('exp_month') ]);
		$exp_month_field = new field('select', [
			'name'        => 'exp_month',
			'class'       => 'input-block-level',
			'id'          => 'cart-exp-month',
			'data-stripe' => 'exp-month',
			'value'       => take($cart, 'exp_month'),
			'options'     => credit::get_months(),
		]);

		$exp_year_group = [ 'label' => "Expiration Year", 'class' => $cart->error_class('exp_year') ];
		$exp_year_help  = new field('help', [ 'text' => $cart->take_error('exp_year') ]);
		$exp_year_field = new field('select', [
			'name'        => 'exp_year',
			'class'       => 'input-block-level',
			'id'          => 'cart-exp-year',
			'data-stripe' => 'exp-year',
			'value'       => take($cart, 'exp_year'),
			'options'     => credit::get_years(),
		]);
				

		$this->form
			->fieldset('Shipping')
			->group($name_group, $name_field, $name_help)
			->group($email_group, $email_field, $email_help)
			->group($address_group, $address_field, $address_help)
			->fieldset('Payment')
			->group($card_group, $card_field, $card_help)
			->group($cvc_group, $cvc_field, $cvc_help)
			->group($exp_month_group, $exp_month_field, $exp_month_help)
			->group($exp_year_group, $exp_year_field, $exp_year_help)
			;
	}

}
