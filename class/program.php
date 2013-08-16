<?
class program {

	private $instance;
	private $ok; # general "success" of program
	private $opt_groups = [];

	function __construct() {
		require_once config::$setting['vendor_dir'].'/clipclop/ClipClop.php';
		$this->instance	= new ClipClop;
		$this->option([
			'short' => 'h',
			'long'  => 'help',
			'help'  => 'Help',
		]);
		$this->ok = true; # assume the program was a success until $this->fail()
		return $this;
	}

	function option(array $options) {
		$this->instance->addOption($options);
		$this->opt_groups[] = $options;
		return $this;
	}

	function get($option) {
		return $this->instance->getOption($option);
	}

	function getif($option, $gets_message="?", array $gets_options = []) {
		return $this->get($option) ? $this->get($option) : gets($gets_message, $gets_options);
	}

	function help() {
		return "Usage: {$this->instance->getUsage()}\n ";
	}

	function ok() {
		return $this->ok;
	}

	function fail() {
		$this->ok = false;		
		return $this;
	}

	function check($boolean) {
		$this->ok = (bool) $boolean;
		return $this;
	}

}
