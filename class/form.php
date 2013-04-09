<?
class form {

	public $html;
	private $control_groups; 
	private $field_head;

	function __construct() {
		$this->html = '';	
		$this->has_head = false;
		$this->control_groups = true;
	}

	function __toString() {
		return $this->render();	
	}

	function open($action='#', $method='post', array $attrs=array()) {
		$this->html .= '<form action="'. $action .'" method="'. strtoupper($method) .'"';
		$this->html .= field::build_attributes($attrs) .'>';
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

	function group($label='') {
		$args = func_get_args();		
		$argpos = 1;

		if ($this->control_groups) {
			$this->html .= '<div class="control-group"';
			if (is_string($args[$argpos]))
				$this->html .= ' '.$args[$argpos++];
			$this->html .= '">';
		}

		$inputs = count($args) - $argpos;

		if ($label) {
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

}
