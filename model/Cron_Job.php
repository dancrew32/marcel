<?
class Cron_Job extends model {
	static $table_name = 'cron_jobs';

	function should_run($time = false) {
		if (!$this->active) return false;
		$time = is_string($time) ? strtotime($time) : time();
		$time = explode(' ', date('i G j n w', $time));
		$crontab = explode(' ', $this->frequency);
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
