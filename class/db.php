<?
$GLOBALS['_db_queries'] = [];
class db {
	static function init() {
		$config = config::$setting;
		require_once "{$config['vendor_dir']}/activerecord/ActiveRecord.php";
		ActiveRecord\Config::initialize(function($cfg) use ($config) {
			$cfg->set_model_directory($config['model_dir']);
			$cfg->set_connections([
				'default' => "mysql://{$config['db_user']}:{$config['db_pass']}@{$config['db_host']}/{$config['db_name']}",
			]);
			$cfg->set_default_connection('default');
		});
	}

	# Don't use this.
	static function get_array($query) {
		$config = config::$setting;
		$pdo    = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']}", $config['db_user'], $config['db_pass']);
		$prep   = $pdo->prepare($query);
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
}
