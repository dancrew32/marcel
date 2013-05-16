<?
class User_Type extends model {
	static $table_name = 'user_types';
	static $default_user = 'user';

/*
 * RELATIONSHIPS
 * Product_Category > Product_Type > Product
 */
	static $has_many = [
		[ 'users', 'class_name' => 'User' ],
	];

/*
 * VALIDATION
 */

	static $validates_presence_of = [
		['name', 'message' => 'must be added!'],
		['slug', 'message' => 'must be added!'],
	];

	static $validates_uniqueness_of = [
		['name', 'message' => 'is taken'],
		['slug', 'message' => 'is taken'],
	];

	/*
	# Callbacks 
	# (array of local function names)
	static $before_save = [];                 # called before a model is saved
	static $before_create = [];               # called before a NEW model is to be inserted into the database
	static $before_update = [];               # called before an existing model has been saved
	static $before_validation = [];           # called before running validators
	static $before_validation_on_create = []; # called before validation on a NEW model being inserted
	static $before_validation_on_update = []; # same as above except for an existing model being saved
	static $before_destroy = [];              # called after a model has been deleted
	static $after_save = [];                  # called after a model is saved
	static $after_create = [];                # called after a NEW model has been inserted into the database
	static $after_update = [];                # called after an existing model has been saved
	static $after_validation = [];            # called after running validators
	static $after_validation_on_create = [];  # called after validation on a NEW model being inserted
	static $after_validation_on_update = [];  # same as above except for an existing model being saved
	static $after_destroy = [];               # called after a model has been deleted
	*/

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

	static function default_id() {
		return self::find_by_slug(self::$default_user)->id;
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
			case 'id':
				return $this->read_attribute($name);
			default:
				$out = h($this->read_attribute($name));
		}
		return $out;
	}

}

