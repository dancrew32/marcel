<?
class model extends ActiveRecord\Model {

/*
 * STATIC
 */
	static function get_offset($page, $rpp) {
		return ($page - 1) * $rpp;
	}

	static function collection_to_json($collection) {
		return array_map(function($model) {
			return $model->to_json();
		}, $collection);
	}

/*
 * INSTANCE
 */
	function take_error($key) {
		if (!$this->errors) return false;
		$errors = take($this->errors, $key);
		return $errors ? implode("<br>", $errors) : false;
	}

	function error_class($key) {
		return take($this->errors, $key) ? 'error' : '';
	}

	# Flash set errors and previously entered values
	function to_note() {
		$out = $this->to_array();
		if ($this->errors)
			$out['errors'] = $this->errors->to_array();
		note::set($this->note_key(), json_encode($out), true);
	}

	# Flash get model and errors
	function from_note() {
		$note = note::get($this->note_key(), true);
		if (!$note) return $this;
		$json = json_decode($note, true); 
		return new $this($json);
	}

	function note_key() {
		return get_class($this).':form';
	}

}
