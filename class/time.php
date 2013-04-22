<?
class time {
	const ONE_YEAR   = 31557600;
	const ONE_DAY    = 86400;
	const ONE_HOUR   = 3600;
	const ONE_MINUTE = 60;

	static function now() {
		return date('Y-m-d H:i:s');	
	}

	static function ago($date) {
		if (!$date) return '';
		$periods = ["second", "minute", "hour", "day", "week", "month", "year", "decade"];
		$lengths = ["60","60","24","7","4.35","12","10"];
		$now = time();
		$unix_date = $date->getTimestamp();
		$tense = 'ago';

		if ($now > $unix_date)
			$difference = $now - $unix_date;
		else {
			$difference = $unix_date - $now;
			$tense = "from now";
		}

		for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++)
			$difference /= $lengths[$j];
			$difference = round($difference);
			if ($difference != 1)
				$periods[$j].= "s";

		return "{$difference} {$periods[$j]} {$tense}";
	}
}
