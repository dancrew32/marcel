<?
class Product_Type extends model {
	static $table_name = 'product_types';

/*
 * RELATIONSHIPS
 * Product_Category > Product_Type > Product
 */
	static $belongs_to = [
		[ 'category', 'class_name' => 'Product_Category' ]	
	];

	static $has_many = [
		[ 'products', 'class_name' => 'Product' ],
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

	# Validation
	static $validates_presence_of = [
		['name', 'message' => 'must be added!'],
	];

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
			case 'category':
			case 'products':
				return $this->read_attribute($name);
			default:
				$out = h($this->read_attribute($name));
		}
		return $out;
	}

}
