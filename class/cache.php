<?
class cache {

	/*
	 * MEMCACHED
	 */
	private static $instance = null;	

	public static function mc() {
		if (is_null(self::$instance)) {
			self::$instance = new Memcached('default');	
			self::$instance->addServer('localhost', 11211);
		}
		return self::$instance;
	}

	static function get($key, &$found=false, $unserialize=false) {
		$found = false;
		if (CACHE_BUST) 
			return null;
		$key = md5($key);
		$mc = self::mc();
		$data = $mc->get($key);
		if (isset($data{0}))
			$found = true;
		if ($unserialize)
			$data = unserialize($data);
		return $data;
	}

	static function set($key, $data, $expire=30, $serialize=false) {
		$key = md5($key);
		$mc = self::mc();
		if ($serialize)
			$data = serialize($data);
		$mc->set($key, $data, $expire);	
	}

	static function delete($key) {
		$key = md5($key);
		if (!isset($key{0}))
			return false;
		$mc = self::mc();
		$mc->delete($key);
		return true;
	}

	static function flush() {
		$mc = self::mc();
		$mc->flush();	
	}

	// $key = cache::keygen(__CLASS__, __FUNCTION__, $id);
	static function keygen($class, $method, $unique_id='') {
		return APP_NAME.$class.$method.$unique_id;
	}



	/*
	 * APC (If you don't want to use memcached)
	 */
	/*
	static function get($key) {
		$key = md5($key);
		if (apc_exists($key))
			return apc_fetch($key);
		return false;
	}

	static function set($key, $val, $len=360) {
		return apc_add(md5($key), $val, $len);	
	}

	static function delete($key) {
		$key = md5($key);
		return apc_delete($key);
	}
	*/

}
