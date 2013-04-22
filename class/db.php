<?
/*
 * Helpers for database stuff
 */
class db {
	static function init() {
		require_once VENDOR_DIR.'/activerecord-dev/ActiveRecord.php';
		ActiveRecord\Config::initialize(function($cfg) {
			$cfg->set_model_directory(MODEL_DIR);
			$cfg->set_connections([
				'default' => 'mysql://'. DB_USER .':'. DB_PASS .'@'. DB_HOST .'/'. DB_NAME,
			]);
			$cfg->set_default_connection('default');
		});
	}
	static function dtnow($timestamp=false) {
		if ($timestamp)
			return date('Y-m-d H:i:s', $timestamp);
		return date('Y-m-d H:i:s');	
	}
	static function dnow() {
		return date('Y-m-d');	
	}
}
