<?
class form {

	private $html;
	private $control_groups; 
	private $field_head;
	private $angular;

	function __construct(array $o=[]) {
		$o = array_merge([
			'angular' => false,
		], $o);
		$this->html = '';	
		$this->has_head = false;
		$this->control_groups = true;
		$this->angular = $o['angular'];
		return $this;
	}

	function __toString() {
		return $this->render();	
	}

	function open($action='#', $method='post', array $attrs=array()) {
		app::asset('class/form', 'js');
		$this->html .= '<form ';
		if (!$this->angular) {
			$this->html .= 'action="'. $action .'" method="'. strtoupper($method) .'"';
			if ($method == 'post')
				$this->html .= ' enctype="multipart/form-data"';
		}
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
		return $this->custom('<br>');
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

			foreach ($args as $inp) {
				if (!isset($inp->attrs)) continue;
				$id = take($inp->attrs, 'id', false);
				if (!$id) continue;
				$this->html .= " for=\"{$id}\"";
				break;
			}

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

	//function groupify($vars, &$form) {
		//$cache = [];
		//foreach ($vars as $k => $v) {
			//$parts = explode('_', $k);
			//$suffix = array_pop($parts);
			//$name = implode('_', $parts);
			//if ($

			//$group = null;
			//$field = null;
			//$help = null;
			//switch ($suffix) {
				//case 'group':	
					//$
					//break;
				//case 'field':	
					//break;
				//case 'help':	
					//break;
			//}
		//}
		//$form	
			
	//}

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
