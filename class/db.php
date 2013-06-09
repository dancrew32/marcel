<?
class db {
	static function init() {
		require_once VENDOR_DIR.'/activerecord/ActiveRecord.php';
		ActiveRecord\Config::initialize(function($cfg) {
			$cfg->set_model_directory(MODEL_DIR);
			$cfg->set_connections([
				'default' => 'mysql://'. DB_USER .':'. DB_PASS .'@'. DB_HOST .'/'. DB_NAME,
			]);
			$cfg->set_default_connection('default');
		});
	}

	# Don't use this.
	static function get_array($query) {
		$pdo = new PDO('mysql:host='. DB_HOST .';dbname='.DB_NAME, DB_USER, DB_PASS);
		$prep = $pdo->prepare($query);
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
}
