<?
class Stock extends model {
	static $table_name = 'stocks';

	const CACHE_TIMEOUT = 1;

/*
 * EVENTS
 */
	static $after_update  = ['update_cache'];
	static $after_destroy = ['delete_cache'];

/*
 * STATIC
 */
	static function create_symbol($symbol) {
		$data = (array) stock_market::quote($symbol);
		if (!count($data)) return false;
		ksort($data);
		$stock = new self;
		$stock->symbol = $symbol;
		$stock->name   = $data['Name'];
		$stock->data   = json_encode($data);
		return $stock->save();
	}

	static function symbol($symbol) {
		$offset = new DateTime('now');
		$cache_key = self::cache_key($symbol);
		$timeout = time::ONE_MINUTE * self::CACHE_TIMEOUT;

		//cache::delete($cache_key);
		$stock = cache::get($cache_key, $found, true);
		if (!$found) {
			$stock = self::find_by_symbol($symbol);
			if (!$stock)
				$stock = self::create_symbol($symbol);
			cache::set($cache_key, $stock, $timeout, true);
		}

		if ($stock && ($stock->updated_at->format('U') + $timeout) < $offset->format('U'))
			$stock->update_symbol();

		return json_decode(take($stock, 'data', '{}'));
	}

	static function symbols(array $symbols) {
		return array_map(function($symbol) {
			return self::symbol($symbol);
		}, $symbols);
	}

	static function cache_key($symbol) {
		return cache::keygen(__CLASS__, __FUNCTION__, $symbol);
	}

/*
 * INSTANCE
 */
	function __set($name, $value) {
		switch ($name) {
			default: 
				$this->assign_attribute($name, $value);
		}
	}

	function &__get($name) {
		switch ($name) {
			default:
				$out = $this->read_attribute($name);
		}
		return $out;
	}

	function delete_cache() {
		return cache::delete(self::cache_key($this->symbol));
	}

	function update_cache() {
		$timeout = time::ONE_MINUTE * self::CACHE_TIMEOUT;
		return cache::set(self::cache_key($this->symbol), $this, $timeout, true);
	}

	function update_symbol() {
		$data = (array) stock_market::quote($this->symbol);
		if (!count($data)) return false;
		ksort($data);
		$this->data = json_encode($data);
		return $this->save();
	}

}
