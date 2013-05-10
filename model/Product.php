<?
class Product extends model {
	static $table_name = 'products';


/*
 * RELATIONSHIPS
 * Product_Category > Product_Type > Product
 */
	static $belongs_to = [
		[ 'type',     'class_name' => 'Product_Type' ],
	];

	/*
	<? foreach (Product_Category::all() as $category): ?>
		<? foreach ($category->types as $type): ?>
			<? foreach($type->products as $product): ?>
				<?= $product ?>
			<? endforeach ?>
		<? endforeach ?>
	<? endforeach ?>
	*/

/*
 * STATIC
 */

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
			case 'type':
				return $this->read_attribute($name);
			default:
				$out = h($this->read_attribute($name));
		}
		return $out;
	}

}
