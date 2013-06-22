<?
class Cron_Job extends model {
	static $table_name = 'cron_jobs';


/*
 * VALIDATION
 */
	static $validates_presence_of = [
		['name'], 
		['script'],
	];
	static $validates_uniqueness_of = [
		['script', 'message' => "exists in cron table"],
	];
	static $validates_size_of = [
		['frequency', 'minimum' => 9, 'too_short' => 'must be at least 9 characters long']
	];
	static $validates_format_of = [
		['frequency', 'with' => '/[^a-zA-Z<>]*/'],
	];


/*
 * INSTANCE
 */
	function __set($name, $value) {
		switch ($name) {
			case 'name':
			case 'script':
			case 'frequency':
			case 'description':
				$this->assign_attribute($name, trim($value));
				break;
			default: 
				$this->assign_attribute($name, $value);
		}
	}

	function &__get($name) {
		switch ($name) {
			default:
				$out = h($this->read_attribute($name));
		}
		return $out;
	}

	function script_exists() {
		return is_file($this->script);	
	}

	function should_run($time = false) {
		if (!$this->active) return false;
		if (!$this->script_exists()) return false;
		return self::build_cron_expression($this->frequency, $time);
	}

/*
 * STATIC
 */
	static function seed() {
		$scripts = glob(SCRIPT_DIR.'/cron.*.php');
		$data = [
			'MySQL Optimize Database' => [
				'active'      => 0,
				'script'      => '',
				'frequency'   => '0 0 1 * *',
				'description' => 'optimize database every month one the first at midnight',
			],
		];
		foreach ($data as $k => $v)
			self::create([
				'name'        => $k, 
				'active'      => take($v, 'active'),
				'frequency'   => take($v, 'frequency'),
				'description' => take($v, 'description'),
			]);
	}

	static function build_cron_expression($frequency, $time) {
		$time = is_string($time) ? strtotime($time) : time();
		$time = explode(' ', date('i G j n w', $time));
		$crontab = explode(' ', $frequency);
		foreach ($crontab as $k => &$v) {
			$v = explode(',', $v);
			$regexps = [
				'/^\*$/', # every 
				'/^\d+$/', # digit 
				'/^(\d+)\-(\d+)$/', # range
				'/^\*\/(\d+)$/' # every digit
			];
			$content = [
				"true", # every
				"{$time[$k]} === 0", # digit
				"($1 <= {$time[$k]} && {$time[$k]} <= $2)", # range
				"{$time[$k]} % $1 === 0" # every digit
			];
			foreach ($v as &$v1)
				$v1 = preg_replace($regexps, $content, $v1);
			$v = '('.implode(' || ', $v).')';
		}
		$crontab = implode(' && ', $crontab);
		return (bool) eval("return {$crontab};");
	}

	static function scripts() {
		$scripts = glob(SCRIPT_DIR.'/cron.*.php');
		$remove = preg_grep('/base/', $scripts);
		foreach ($scripts as $k => $s) {
			if (!in_array($s, $remove)) continue;
			unset($scripts[$k]);
		}
		return $scripts;
	}

	static function find_scripts($query) {
		$scripts = self::scripts();
		$matches = [];
		foreach ($scripts as $k => $s) {
			$name = util::explode_pop('/', $s);
			similar_text($name, $query, $percent);
			if (floor($percent) < 8) continue;
			$matches[] = [
				'k' => $s,
				'v' => $name,
			];
		}
		return $matches;
	}
}
