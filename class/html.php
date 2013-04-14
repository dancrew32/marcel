<?
class html {
	static function a($href, $text, $cls=false) {
		$cls = $cls ? " class=\"{$cls}\"" : '';
		return "<a href=\"{$href}\"{$cls}>{$text}</a>";
	}

	static function btn($href, $text, $type='') {
		$cls = 'btn';
		if ($type)
			$cls .= " btn-{$type}";
		return self::a($href, $text, $cls);	
	}

	static function ul(array $data) {
		return '<ul>'. _list($data, 'li') .'</ul>';
	}

	static function ol(array $data) {
		return '<ol>'. _list($data, 'li') .'</ol>';
	}

	static function build_attributes(array $attrs=[]) {
		$html = '';
		foreach($attrs as $k => $v){
            switch ($k) {
				case 'class':
					$html .= " {$k}=\"". (is_array($v) ? implode(' ', $v) : $v) .'"';
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
					if (isset($v{0}) && in_array($v{0}, ['[', '{'])) # js array or object 
						$html .= " {$k}='{$v}'";
                    else if ($k != 'prepend' && $k != 'append')
                        $html .= " {$k}=\"{$v}\"";
            }
        }
		return $html;
	}

	static function build_prepend(array $attrs=[]) {
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

	static function build_append(array $attrs=[]) {
		if (isset($attrs['append']))
			return '<span class="add-on">'.$attrs['append'].'</span></div>';

		if (isset($attrs['prepend'])) return '</div>';
	}

	static function table($data=[], array $options=[]) {
		$options = array_merge([
			'delete_col' => false,
			'delete_url' => '#',
			'primary_key' => 'id',
			'hidden_columns' => [],
		], $options);
		$table = new table((array) $data);	
		foreach ($options as $k => $v)
			$table->{$k} = $v;
		return $table;
	}

	private static function _list(array $data, string $type) {
		$html = '';
		$count = 0;
		$len = count($data) - 1;
		foreach	($data as $d) {
			if (!$count)
				$cls = ' class="alpha"';
			if ($count == $len)
				$cls = ' class="omega"';
			$html .= "<$type}{$cls}>{$d}</{$type}>";
			$count++;
		}
		return $html;
	}

}
