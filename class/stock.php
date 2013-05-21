<?
class stock {
	static $instance = false;

	static function init() {
		if (self::$instance) return true;
		require_once VENDOR_DIR.'/yahoo_finance/lib/YahooFinance/YahooFinance.php';
		self::$instance = new YahooFinance;
	}

	static function quote($symbol) {
		self::init();
		$json = self::$instance->getQuotes($symbol);
		$data = json_decode($json);
		$quote = $data->query->results->quote;
		return $quote;
	}

	static function history($symbol) {
		self::init();
		$start = new DateTime('2000-05-18 00:00:00');
		$end = new DateTime('2013-05-01 00:00:00');
		$json = self::$instance->getHistoricalData($symbol, $start, $end);
		pd($json);
		$data = json_decode($json);
		$quote = $data->query->results;
		return $quote;
	}
}
