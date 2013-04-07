<?
class form {

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
                if ($pre) $html.=' ';
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

		if (isset($attrs['prepend']))
			return '</div>';
	}

	static function open($action='#', $method='post', array $attrs=array()) {
		$html = '<form action="'. $action .'" method="'. strtoupper($method) .'"';
		$html .= self::build_attributes($attrs) .'>';
		return $html;
	}

	static function input(array $attrs=array()) {
		$html = self::build_prepend($attrs); 
		$type = take($attrs, 'type', 'text');
		$html .= '<input type="'. $type .'"'. self::build_attributes($attrs) .' />';
		$html .= self::build_append($attrs);
		return $html;
	}

	static function button($label='submit', array $attrs=array()) {
		if (!isset($attrs['class']))
			$attrs['class'] = 'btn';
		return '<button'. self::build_attributes($attrs) .'>'.$label.'</button>';
	}

	static function checkbox($label='', array $attrs=array(), $inline=false) {
		$html = '<label class="checkbox';
		if ($inline) $html .= ' inline';
		$html .= '"><input type="checkbox"'. self::build_attributes($attrs) .' />'. $label .'</label>';
		return $html;
	}

}
