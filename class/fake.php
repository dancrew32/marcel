<?
class fake {
	# wrapper for https://github.com/fzaninotto/Faker
	private static $instance = false;

	# echo fake::email()
	static function __callStatic($method, $args) {
		if (!self::$instance) {
			require_once config::$setting['vendor_dir'].'/faker/src/autoload.php';		
			self::$instance = Faker\Factory::create();
		}
		return self::$instance->$method(take($args, 0));
	}	
}
