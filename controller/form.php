<?
class controller_form extends controller_base {
	function input($o) {
		$this->name = take($o, 'name');
		$this->checked = take($o, 'checked', false);
		$this->classes = take($o, 'classes', false);
		$this->data = take($o, 'data', []);
		$this->desc = take($o, 'desc', false);
		$this->disabled = take($o, 'disabled', false);
		$this->id = take($o, 'id', $this->name);
		$this->label = take($o, 'label', false);
		$this->noauto = take($o, 'noauto', false);
		$this->placeholder = take($o, 'placeholder', false);
		$this->range = take($o, 'range', false);
		$this->selected = take($o, 'selected', false);
		$this->type = take($o, 'type', 'text');
		$this->validate = take($o, 'validate', false);
		$this->value = take($o, 'value');
		$this->size = take($o, 'size', false);
		$this->minsize = take($o, 'minsize', false);
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
	function submit($o) {
		$this->type = 'submit';
		$this->text = take($o, 'text', 'Submit');	
		$this->classes = take($o, 'classes', false);
		$this->disabled = take($o, 'disabled', false);
	}
}
