<?
class event {

	private static $events = array();

	static function all() {
		return self::$events;
	}

	static function on($event, $callback) {
		if (!is_callable($callback))
			throw new InvalidArgumentException();

		if (empty(self::$events[$event]))
			self::$events[$event] = [];

		array_push(self::$events[$event], $callback);
	}

	static function emit($event, $params = '') {
		$events = self::all();
		$params = func_get_args();
		array_shift($params);

		foreach ($events[$event] as $event)
			call_user_func_array($event, $params);
	}

	static function off($event) {
		self::$events[$event] = [];
	}

	static function flush() {
		self::$events = [];
	}

}
