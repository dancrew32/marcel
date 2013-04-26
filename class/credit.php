<?
class credit {
	static function get_instance() {
		require_once VENDOR_DIR.'/stripe/lib/Stripe.php';
		$key = api::get_key('stripe')[ENV]['secret'];
		Stripe::setApiKey($key);
	}	

	static function get_public_key() {
		return api::get_key('stripe')[ENV]['public'];
	}

	static function get_months() {
		return [
			'01' => 'January (01)',
			'02' => 'February (02)',
			'03' => 'March (03)',
			'04' => 'April (04) ',
			'05' => 'May (05)',
			'06' => 'June (06)',
			'07' => 'July (07)',
			'08' => 'August (08)',
			'09' => 'September (09)',
			'10' => 'October (10)',
			'11' => 'November (11)',
			'12' => 'December (12)',
		];	
	}

	static function get_years() {
		$cur = (int) date('Y');
		$years = [];
		for ($i = 1; $i < 10; $i++) {
			$year = $cur++;
			$years[$year] = $year;
		}

		return $years;
	}

	static function charge(array $o=[], $env='DEV') {
		self::get_instance($env);

		$o = array_merge([
			'number'      => '4242424242424242', # required (MC:5555555555554444, AE:371449635398431)
			'exp_month'   => 5,  # required
			'exp_year'    => 2015, # required
			'amount'      => 2000,
			'token'       => false,
			'currency'    => 'usd',
			'description' => '',
		], $o);

		$charge_data = [];
		if ($o['token']) {
			$charge_data['card'] = $o['token'];
		} else {
			$charge_data['card'] = [
				'number'    => take($o, 'number'),
				'exp_month' => take($o, 'exp_month'),
				'exp_year'  => take($o, 'exp_year'),
			];
		}
		$charge_data['amount'] = take($o, 'amount');
		$charge_data['currency'] = take($o, 'currency');
		$charge = Stripe_Charge::create($charge_data);

		return $charge;
	}
}
