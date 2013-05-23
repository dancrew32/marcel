<?
class User_Permission extends model {
	static $table_name = 'user_permissions';

	static $instance = [];

	# Callbacks 
	static $after_save = ['reload_cache'];

/*
 * STATIC
 */

	static function init() {
		$cache_key = self::cache_key();
		self::$instance = cache::get($cache_key, $found, true);
		if (!$found) {
			$up = self::find('all', [
				'select' => 'user_type_id, slug',
				'joins'  => 'inner join features on features.id = user_permissions.feature_id',  
			]);
			foreach ($up as $p)
				self::$instance[$p->user_type_id][] = $p->slug;
			cache::set($cache_key, self::$instance, time::ONE_HOUR, true);
		}
	}

	static function cache_key() {
		return APP_NAME.__CLASS__.__FUNCTION__;
	}

	static $belongs_to = [
		[ 'feature', 'class_name' => 'Feature' ]	
	];

/*
 * INSTANCE
 */

	//function __toString() {
		//return $this->feature->name;
	//}

	function __set($name, $value) {
		switch ($name) {
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

	function reload_cache() {
		return cache::delete(self::cache_key());
		self::init();
	}

}
