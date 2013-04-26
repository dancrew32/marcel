<?
class Worker extends model {
	static $table_name = 'workers';

/*
 * VALIDATION
 */
	static $validates_presence_of = [
		['class'],
		['method'],
		['hash'],
	];

	static $validates_uniqueness_of = [
		['hash'],
	];


/*
 * STATIC
 */
	static function add(array $options=[]) {
		$options = array_merge([
			'class'  => false,
			'method' => false,
			'args'   => null,
			'run_at' => false,
		], $options);

		if (!isset($options['class']{0}) || !isset($options['method']{0}))
			return false;
		$callable = is_callable("{$options['class']}::{$options['method']}") === true;
		if (!$callable)
			return false;

		$w = new Worker;
		$w->class = $options['class'];
		$w->method = $options['method'];
		$w->apply_args($options['args']);
		$w->make_hash();

		$run_at = strtotime($options['run_at']);
		if ($run_at > 0)
			$this->run_at = date('Y-m-d H:i:s', $run_at);

		return $w->save();
	}


/*
 * INSTANCE
 */
	function apply_args($args) {
		$this->args = serialize($args);
	}

	function make_hash() {
		$this->hash = md5($this->class.$this->method.$this->args);
	}

	function run($thread_id = 0) {
		if ($this->active) return true;
		$this->active = true;
		$this->save();
		if (CLI)
			yellow("{$thread_id}: running {$this->class}::{$this->method}\n");

		try {
			$callable = is_callable("{$this->class}::{$this->method}") === true;
			if (!$callable)
				return false;

			$args = $this->args ? unserialize($this->args) : null;
			call_user_func("{$this->class}::{$this->method}", $args);
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

}
