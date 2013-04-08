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
			case 'date':
			case 'datetime':
			case 'datetime-local':
			case 'email':
			case 'month':
			case 'number':
			case 'range':
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

		return $this->$type();
	}

	function __toString() {
		$this->render();	
	}

	function input() {
		$this->html .= self::build_prepend($this->attrs); 
		$type = take($this->attrs, 'type', 'text');
		$this->html .= '<input type="'. $type .'"'. self::build_attributes($this->attrs) .' />';
		$this->html .= self::build_append($this->attrs);

		return $this;
	}

	function button() {
		if (!isset($this->attrs['class']))
			$this->attrs['class'] = 'btn';
		if (isset($this->attrs['text'])) {
			$text = $this->attrs['text'];	
			unset($this->attrs['text']);
		}

		$this->html .= '<button'. self::build_attributes($this->attrs) .'>'.$text.'</button>';
		return $this;
	}

	function checkbox($label='', $inline=false) {
		$this->html .= '<label class="checkbox';
		if ($inline) $this->html .= ' inline';
		$this->html .= '"><input type="checkbox"'. self::build_attributes($this->attrs) .' />'. $label .'</label>';
		return $this;
	}

	function select() {
		$options = $this->pick('options', 'array');
		$selected = $this->pick('value', 'array');
		$this->html .= '<select'. self::build_attributes($this->attrs) .'>';

		foreach ($options as $k => $v) {
			$this->html .= "<option value=\"{$v}\"";
			if (in_array($k, $selected, true))
				$this->html .= ' selected';
			$this->html .= ">{$v}</option>";
		}
		$this->html .= '</select>';
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

	static function build_attributes(array $attrs=array()) {
		$html = '';
		foreach($attrs as $k => $v){
            switch ($k) {
                case 'checked':
					if ($v) $html .=' checked'; break;
				case 'autocomplete': 
					if (!$v) $html .=' autocomplete="off"'; break;
                case 'required':
                    if ($v) $html .=' required'; break;
                case 'multiple':
                    if ($v) $html .=' multiple'; break;
				case 'readonly':
					if ($v) $html .=' readonly'; break;
				case 'autofocus':
					if ($v) $html.=' autofocus'; break;
                default:
                    if ($k != 'prepend' && $k != 'append')
                        $html .= " {$k}=\"{$v}\"";
            }
        }
		return $html;
	}

	static function build_prepend(array $attrs=array()) {
		$html = '';
        $pre = take($attrs, 'prepend', null);
        $app = take($attrs, 'append', null);

        if ($pre || $app){
            $html = '<div class="';

            if ($pre) $html .= 'input-prepend';

            if ($app) {
                if ($pre) $html .= ' ';
                $html .= 'input-append';
            }

            $html .= '">';

            if ($pre) $html .= "<span class=\"add-on\">{$pre}</span>";
        }

        return $html;
	}

	static function build_append(array $attrs=array()) {
		if (isset($attrs['append']))
			return '<span class="add-on">'.$attrs['append'].'</span></div>';

		if (isset($attrs['prepend'])) return '</div>';
	}


	static function has_class($cls, $attrs) {
		if (!isset($attrs['class'])) return false;
		return strpos($attrs['class'], $cls) !== false;
	}


}
