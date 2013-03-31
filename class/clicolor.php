<?
class clicolor {
	private static $foreground = array(
		'black' => '0;30',
		'dark_gray' => '1;30',
		'red' => '0;31',
		'bold_red' => '1;31',
		'green' => '0;32',
		'bold_green' => '1;32',
		'brown' => '0;33',
		'yellow' => '1;33',
		'blue' => '0;34',
		'bold_blue' => '1;34',
		'purple' => '0;35',
		'bold_purple' => '1;35',
		'cyan' => '0;36',
		'bold_cyan' => '1;36',
		'white' => '1;37',
		'bold_gray' => '0;37',
	);

	private static $background = array(
		'black' => '40',
		'red' => '41',
		'magenta' => '45',
		'yellow' => '43',
		'green' => '42',
		'blue' => '44',
		'cyan' => '46',
		'light_gray' => '47',
	);

	static function fg($color, $string) {
		if (!isset(self::$foreground[$color]))
			throw new Exception('Foreground color is not defined');

		return "\033[" . self::$foreground[$color] . "m" . $string . "\033[0m";
	}

	static function bg($color, $string) {
		if (!isset(self::$background[$color]))
			throw new Exception('Background color is not defined');

		return "\033[" . self::$background[$color] . 'm' . $string . "\033[0m";
	}

	static function all_fg() {
		foreach (self::$foreground as $color => $code)
			echo "$color - " . self::fg($color, 'Hello, world!') . PHP_EOL;
	}

	/**
	 * See what they all look like
	 */
	static function all_bg() {
		foreach (self::$background as $color => $code)
			echo "$color - " . self::bg($color, 'Hello, world!') . PHP_EOL;
	}
}
