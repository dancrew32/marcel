<?
class controller_form extends controller_base {
	# http://twitter.github.io/bootstrap/base-css.html#forms
	function input($o) {
		$this->label = take($o, 'label', false);
		$this->attrs = $o;
	}		
	function textarea($o) {
		$this->name = take($o, 'name');
		$this->id = take($o, 'id', $this->name);
		$this->type = __FUNCTION__;
		$this->placeholder = take($o, 'placeholder', false);
		$this->label = take($o, 'label', false);
		$this->value = take($o, 'value');
		$this->classes = take($o, 'classes', []);
		$this->validate = take($o, 'validate', false);
		$this->desc = take($o, 'desc', false);
		$this->disabled = take($o, 'disabled', false);
	}
	function select($o) {
		$this->name = take($o, 'name');
		$this->id = take($o, 'id', $this->name);
		$this->type = __FUNCTION__;
		$this->label = take($o, 'label', false);
		$this->multiple = take($o, 'multiple', false);
		if ($this->multiple)
			$this->value = explode(',', take($o, 'value'));
		else
			$this->value = take($o, 'value');
		$this->classes = take($o, 'classes', []);
		$this->options = take($o, 'options', []);
		$this->validate = take($o, 'validate', false);
		$this->desc = take($o, 'desc', false);
		$this->disabled = take($o, 'disabled', false);
	}
	function button($o) {
		$this->text = take($o, 'text');
		$this->attrs = $o;
	}
}
