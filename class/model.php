<?
class model extends ActiveRecord\Model {
	static function total() {
		$query = static::find_by_sql('select count(id) as total from '. static::$table_name);
		return $query[0]->total;
	}	

	static function get_offset($page, $rpp) {
		return ($page - 1) * $rpp;
	}

	function get_errors() {
		$errors = [];
		foreach ($this->errors as $k => $er)
			$errors[$k] = $er;
		return $errors;
	}
}
