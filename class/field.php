<?
class field {
	private $html;
	public $attrs;

	function __construct($type, array $attrs=array()) {
		$this->html = '';
		$this->attrs = $attrs;

		switch ($type) {
			case 'button':
				return $this->button();
			case 'submit':
				$this->attrs['type'] = 'submit';
				return $this->button();
			case 'checkbox':
				return $this->checkbox();
			case 'select':
				return $this->select();
			case 'select_multiple':
				$this->attrs['multiple'] = 'multiple';
				return $this->select();
			case 'textarea':
				return $this->textarea();
			case 'radio':
				return $this->radio();
			case 'date':
			case 'datetime':
			case 'datetime-local':
			case 'file':
			case 'hidden':
			case 'image':
			case 'password':
			case 'email':
			case 'month':
			case 'number':
			case 'range':
			case 'reset':
			case 'search':
			case 'tel':
			case 'time':
			case 'url':
			case 'week':
				$this->attrs['type'] = $type;
				return $this->input();
			default: 
				return $this->input();
		}
	}

	function __toString() {
		$this->render();	
	}

	function input() {
		$this->html .= html::build_prepend($this->attrs); 
		$type = take($this->attrs, 'type', 'text');
		$this->html .= '<input type="'. $type .'"'. html::build_attributes($this->attrs) .' />';
		$this->html .= html::build_append($this->attrs);
		return $this;
	}


	function textarea() {
		$value = $this->pick('value');
		$this->html .= '<textarea'. html::build_attributes($this->attrs) .">{$value}</textarea>";
		return $this;
	}

	function button() {
		if (!isset($this->attrs['class']))
			$this->attrs['class'] = 'btn';
		$text = $this->pick('text');
		$this->html .= '<button'. html::build_attributes($this->attrs) .'>'.$text.'</button>';
		return $this;
	}

	function checkbox() {
		$label = $this->pick('label');
		$inline = $this->pick('inline', 'boolean');
		$this->html .= '<label class="checkbox';
		if ($inline) $this->html .= ' inline';
		$this->html .= '"><input type="checkbox"'. html::build_attributes($this->attrs) .' />'. $label .'</label>';
		return $this;
	}

	function select() {
		$options = $this->pick('options', 'array');
		$selected = $this->pick('value', 'array');
		$this->html .= '<select'. html::build_attributes($this->attrs) .'>';

		foreach ($options as $k => $v) {
			$this->html .= "<option value=\"{$v}\"";
			if (in_array($k, $selected, true))
				$this->html .= ' selected';
			$this->html .= ">{$v}</option>";
		}
		$this->html .= '</select>';
		return $this;
	}

	function radio() {
		$label = $this->pick('label');
		$inline = $this->pick('inline', 'boolean');
		$this->html .= '<label class="radio';	
		if ($inline)
			$this->html .= ' inline';
		$this->html .= '"><input type="radio"'. html::build_attributes($this->attrs);
		$this->html .= " />{$label}</label>";
		return $this;
	}

	function render() {
		return $this->html;	
	}



/*
 * PRIVATE
 */

	private function pick($to_pick='', $cast = 'string') {
		try {
			$attribute = take($this->attrs, $to_pick);
			settype($attribute, $cast);
			unset($this->attrs[$to_pick]);
			return $attribute;
		} catch (Exception $ex) { }
		return false;
	}


	static function has_class($cls, $attrs) {
		if (!isset($attrs['class'])) return false;
		return strpos($attrs['class'], $cls) !== false;
	}


}
