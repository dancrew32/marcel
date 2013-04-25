<?
class model extends ActiveRecord\Model {

/*
 * STATIC
 */
	static function total($conditions='') {
		$sql = "select count(id) as total from ". static::$table_name ." {$conditions}";
		$query = static::find_by_sql($sql);
		return $query[0]->total;
	}	

	static function get_offset($page, $rpp) {
		return ($page - 1) * $rpp;
	}

	static function collection_to_json($collection) {
		$out = [];
		foreach ($collection as $model)
			$out[] = $model->to_json();
		return $out;
	}


/*
 * INSTANCE
 */
	function get_errors() {
		$errors = [];
		foreach ($this->errors as $k => $er)
			$errors[$k] = $er;
		return $errors;
	}

	function take_error($key) {
		if (!$this->errors) return false;
		$errors = take($this->errors, $key);
		return $errors ? implode("<br>", $errors) : false;
	}

	function error_class($key) {
		return take($this->errors, $key) ? 'error' : '';
	}

	function to_note($key='a') {
		$out = $this->to_array();
		if ($this->errors)
			$out['errors'] = $this->errors->to_array();
		note::set($key, json_encode($out), true);
	}

	function from_note($key='a') {
		$note = note::get($key, true);
		if (!$note) return $this;
		$json = (array) json_decode($note); 
		return new $this($json);
	}

}
