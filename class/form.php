<?
class form {

	private $html;
	private $control_groups; 
	private $field_head;

	function __construct() {
		$this->html = '';	
		$this->has_head = false;
		$this->control_groups = true;
		return $this;
	}

	function __toString() {
		return $this->render();	
	}

	function open($action='#', $method='post', array $attrs=array()) {
		$this->html .= '<form action="'. $action .'" method="'. strtoupper($method) .'"';
		$this->html .= html::build_attributes($attrs) .'>';
		if (field::has_class('form-horizontal', $attrs))
			$this->control_groups = false;
		return $this;
	}

	function fieldset($title) {
		$this->field_head = true;
		$this->html .= "<fieldset><legend>{$title}</legend>";
		return $this;
	}

	// add without group
	function add() {
		$args = func_get_args();		
		$has_label = is_string($args[0]);
		$field = $has_label ? $args[1] : $args[0]; 
		$is_hidden = take($field->attrs, 'type') == 'hidden';

		if (!$is_hidden && $has_label) {
			$this->html .= '<label';	

			if (take($field->attrs, 'id', false))
				$this->html .= ' for="'. take($field->attrs, 'id') .'"';

			$this->html .= ">{$args[0]}</label>";
		}
		$this->html .= $field->render();
		return $this;
	}

	function br() {
		$this->html .= "<br>";
		return $this;
	}
	
	function custom($text) {
		$this->html .= $text;	
		return $this;
	}

	function group() {
		$args = func_get_args();		
		$attrs = [];
		$argpos = 0;
		if (is_array($args[0])) {
			$attrs = $args[0];
			//unset($args[0]);		
			$argpos = 1;
		}


		if ($this->control_groups) {
			$class = self::pick($attrs, 'class');
			$class = 'control-group'. (isset($class{0}) ? ' '. $class : '');
			$this->html .= "<div class=\"{$class}\">";
		}

		$inputs = count($args) - $argpos;

		$label = self::pick($attrs, 'label');
		if (isset($label{0})) {
			$this->html .= '<label';	

			if ($this->control_groups)
				$this->html .= ' class="control-label"';

			if ($inputs == 1 && take($args[$argpos], 'id', false))
				$this->html .= ' for="'. take($args[$argpos], 'id') .'"';

			$this->html .= ">{$label}</label>";
		}

		if ($this->control_groups)
			$this->html .= '<div class="controls">';

		for ($i = $argpos, $len = $inputs + $argpos; $i < $len; $i++)
			$this->html .= $args[$i]->render();

		if ($this->control_groups)
			$this->html .= '</div></div>';

		return $this;
	}

	function actions() {

		if ($this->control_groups)
			$this->html .= '<div class="form-actions">';	

		$inputs = func_get_args();
		for ($i = 0, $size = count($inputs); $i < $size; $i++)
			$this->html .= $inputs[$i]->render();

		if ($this->control_groups)
			$this->html .= '</div>';

		return $this;
	}

	function render() {
		if ($this->field_head)
			$this->html .= '</fieldset>';
		$this->html .= "</form>\n";
		return $this->html;
	}

	private function pick(&$attrs, $to_pick='', $cast = 'string') {
		try {
			$attribute = take($attrs, $to_pick);
			settype($attribute, $cast);
			unset($attrs[$to_pick]);
			return $attribute;
		} catch (Exception $ex) { }
		return false;
	}

}
