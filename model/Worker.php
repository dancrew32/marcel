<?
class Worker extends model {
	static $table_name = 'workers';

	static function test($args) {
		sleep(1);
		return true;
	}

	static function add(array $options=[]) {
		$options = array_merge([
			'class'  => false,
			'method' => false,
			'args'   => null,
			'run_on' => false,
		], $options);

		if (!isset($options['class']{0}) || !isset($options['method']{0}))
			return false;
		$callable = is_callable("{$options['class']}::{$options['method']}") === true;
		if (!$callable)
			return false;

		$w = new Worker;
		$w->class = $options['class'];
		$w->method = $options['method'];
		$w->set_args($options['args']);
		$w->set_hash();
		if ($w->hash_exists())
			return false;

		$run_on = strtotime($options['run_on']);
		if ($run_on > 0)
			$this->run_on = date('Y-m-d H:i:s', $run_on);
		$w->created_on = time::now();
		$w->save();
	}

	function set_args(array $args) {
		$this->args = serialize($args);
	}

	function get_args() {
		return unserialize($this->args);	
	}

	function set_hash() {
		$this->hash = md5($this->class.$this->method.$this->args);
	}

	function hash_exists() {
		$sql = "select count(id) as total 
				from ". self::$table_name ."
				where hash = '{$this->hash}'";
		$query = Worker::find_by_sql($sql);
		return take($query, 'total') > 0;
	}

	function run($thread_id = 0) {
		if ($this->active) return true;
		$this->active = true;
		$this->active_on = time::now();
		$this->save();
		if (CLI)
			yellow("{$thread_id}: running {$this->class}::{$this->method}\n");

		try {
			call_user_func("{$this->class}::{$this->method}", $this->get_args());
			if (CLI)
				green("{$thread_id}: completed {$this->class}::{$this->method}\n");
			return $this->delete();
		} catch (Exception $e) {
			if (CLI)
				red("{$thread_id}: FAILED {$this->class}::{$this->method}\n");
			$this->active = false;
			# TODO: increment fails
			$this->save();
		}
	}

	/*
	 * These are just examples. delete what you don't need.
	 * * *
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
	*/

}
