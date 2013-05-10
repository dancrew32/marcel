<?
class Product_Category extends model {
	static $table_name = 'product_categories';


/*
 * RELATIONSHIPS
 * Product_Category > Product_Type > Product
 */
	static $has_many = [
		[ 'types', 'class_name' => 'Product_Type' ],
	];


/*
 * VALIDATION
 */
	static $validates_presence_of = [
		['name', 'message' => 'must be added!'],
	];


/*
 * STATIC
 */
	static function options() {
		$types = self::find('all', [
			'select' => 'id, name',
		]);
		$out = [];
		foreach ($types as $t) {
			$out[$t->id] = $t->name;	
		}
		return $out;
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
				$out = $this->read_attribute($name);
		}
		return $out;
	}

}
