<?
class Feature extends model {
	static $table_name = 'features';

/*
 * STATIC
 */
	static function seed() {
		$data = [
			'Cron Job'         => 'cron_job',
			'Join'             => 'join',
			'Login'            => 'login',
			'OCR'              => 'ocr',
			'Phone'            => 'phone',
			'Product'          => 'product',
			'Product Type'     => 'product_type',
			'Product Category' => 'product_category',
			'User'             => 'user',
			'User Type'        => 'user_type',
			'User Permission'  => 'user_permission',
			'Feature'          => 'feature',
			'Worker'           => 'worker',
		];

		foreach ($data as $k => $v)
			self::create(['name' => $k, 'slug' => $v]);
	}


/*
 * INSTANCE
 */

	function __toString() {
		return $this->name;
	}

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

}
