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

	static function seed() {
		$data = [
			'Admin'     => 'admin',
			'Manager'   => 'manager',
			'User'      => 'user',
			'Anonymous' => 'anonymous',
		];

		foreach ($data as $k => $v) {
			$ut = self::create(['name' => $k, 'slug' => $v ]);
			if ($v == 'anonymous') {
				$auth_file = CLASS_DIR.'/auth.php';	
				replace_line_with_match(
					$auth_file, 
					"const ANONYMOUS_USER_TYPE_ID", 
					"\tconst ANONYMOUS_USER_TYPE_ID = {$ut->id};\n"
				);
			}
		}
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

