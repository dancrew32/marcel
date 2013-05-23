<?
class Feature extends model {
	static $table_name = 'features';

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
