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
			case 'submit_add':
				$this->attrs['type'] = 'submit';
				$this->attrs['text'] = 'Add';
				$this->attrs['icon'] = 'plus';
				$this->attrs['data-loading-text'] = html::verb_icon('Adding', 'plus');
				return $this->button();
			case 'submit_update':
				$this->attrs['type'] = 'submit';
				$this->attrs['text'] = 'Update';
				$this->attrs['icon'] = 'edit';
				$this->attrs['data-loading-text'] = html::verb_icon('Updating', 'edit');
				return $this->button();
			case 'checkbox':
				$this->attrs['value'] = 1;
				return $this->checkbox();
			case 'select':
				return $this->select();
			case 'select_multiple':
				$this->attrs['multiple'] = 'multiple';
				return $this->select();
			case 'wysiwyg':
				app::asset('class/wysiwyg', 'js');
				return $this->wysiwyg();
			case 'textarea':
				return $this->textarea();
			case 'radio':
				return $this->radio();
			case 'typeahead':
				app::asset('class/typeahead', 'js');
				if (!isset($this->attrs['class']{0}))
					$this->attrs['class'] = 'typeahead';
				else
					$this->attrs['class'] .= ' typeahead';
				$this->attrs['data-provide'] = 'typeahead';
				$this->attrs['autocomplete'] = false;
				return $this->input();
			case 'file_simple':
				return $this->file_simple();
			case 'file':
				return $this->file();
			case 'date':
			case 'datetime':
			case 'datetime-local':
			case 'hidden':
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
			case 'image':
				$this->attrs['type'] = $type;
				if (!isset($this->attrs['alt']))
					$this->attrs['alt'] = 'submit';
				return $this->input();
			case 'help':
				return $this->help();
			case 'custom':
				return $this->custom();
			default: 
				return $this->input();
		}
	}

	function __toString() {
		return $this->render();	
	}

	function input() {
		$this->html .= html::build_prepend($this->attrs); 
		if (!isset($this->attrs['type']))
			$this->attrs['type'] = 'text';
		$this->html .= '<input '. html::build_attributes($this->attrs) .' />';
		$this->html .= html::build_append($this->attrs);
		return $this;
	}

	function file_simple() {
		$this->html .= '<div class="fileupload fileupload-new" data-provides="fileupload">';
		$this->html .= '<span class="btn btn-file">';
		$this->html .= '<span class="fileupload-new">';
		$icon = $this->pick('icon');
		$icon = isset($icon{0}) ? html::icon($icon) : '';
		$this->html .= "{$icon}{$this->pick('text')}";
		$this->html .= '</span>';
		$this->html .= '<span class="fileupload-exists">';
		$this->html .= 'Change';
		$this->html .= '</span>';
		$this->attrs['type'] = 'file';
		$this->input();
		$this->html .= '</span>';
		$this->html .= '<span class="fileupload-preview"></span>';
		$this->html .= '<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">x</a>';
		$this->html .= '</div>';
	}

	function file() {
		$this->html .= '<div class="fileupload fileupload-new" data-provides="fileupload">';
		$this->html .= '<div class="input-append">';
		$this->html .= '<div class="uneditable-input span3">';
		$this->html .= '<i class="icon-file fileupload-exists"></i> ';
		$this->html .= '<span class="fileupload-preview"></span>';
		$this->html .= '</div>';
		$this->html .= '<span class="btn btn-file">';
		$this->html .= '<span class="fileupload-new">Select file</span>';
		$this->html .= '<span class="fileupload-exists">Change</span>';
		$this->html .= '<input type="file" />';
		$this->html .= '</span>';
		$this->html .= '<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>';
		$this->html .= '</div>';
		$this->html .= '</div>';
	}


	function help() {
		$text = take($this->attrs, 'text', false);
		if (!$text) return $this;
		$text = is_array($text) ? implode('<br>', $text) : $text;
		$inline = take($this->attrs, 'inline', false);
		$this->html .= $inline ? 
			"<span class=\"help-inline\">{$text}</span>" :
			"<p class=\"help-block\">{$text}</p>";
		return $this;
	}

	function textarea() {
		$value = $this->pick('value');
		$this->html .= '<textarea'. html::build_attributes($this->attrs) .">{$value}</textarea>";
		return $this;
	}

	function wysiwyg() {
		$id = take($this->attrs, 'id');
		$toolbar_options = [
			'class'       => 'btn-toolbar',
			'data-role'   => 'editor-toolbar',
			'data-target' => "#{$id}",
		];
		
		$this->html .= '<div'. html::build_attributes($toolbar_options) .'>';
		$tools = [
			'undo'      => 'arrow-left',
			'redo'      => 'arrow-right',
			'bold'      => 'bold',
			'italic'    => 'italic',
		];
		foreach ($tools as $purpose => $icon)
			$this->html .= "<a class=\"btn\" data-edit=\"{$purpose}\"><i class=\"icon-{$icon}\"></i></a>";

		$this->html .= '</div>';
		$this->html .= '<div'. html::build_attributes($this->attrs) .'></div>';
		return $this;
	}


	function button() {
		$icon = $this->pick('icon');
		$icon = isset($icon{0}) ? html::icon($icon) : '';
		if (!isset($this->attrs['class']))
			$this->attrs['class'] = 'btn';
		$text = $this->pick('text');
		$this->html .= '<button'. html::build_attributes($this->attrs) .'>'.$icon.$text.'</button>';
		return $this;
	}

	function checkbox() {
		$label = $this->pick('label');
		$inline = $this->pick('inline', 'boolean');
		$this->html .= '<label class="checkbox';
		if ($inline) 
			$this->html .= ' inline';
		$this->html .= '"><input type="checkbox"'. html::build_attributes($this->attrs) .' />'. $label .'</label>';
		return $this;
	}

	function select() {
		$options = $this->pick('options', 'array');
		$selected = $this->pick('value', 'array');
		$this->html .= '<select'. html::build_attributes($this->attrs) .'>';

		foreach ($options as $k => $v) {
			$this->html .= "<option value=\"{$k}\"";
			if (in_array($k, $selected))
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

	function custom() {
		$this->html .= take($this->attrs, 'text');
		return $this;
	}

	function render() {
		return $this->html;	
	}



/*
 * PRIVATE
 */

	private function pick($to_pick='', $cast='string') {
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
