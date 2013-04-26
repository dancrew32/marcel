<?
class html {

	static function __callStatic($method, $args) {
		switch ($method) {
			case 'a':	
				return is_array($args[0]) ? self::_a($args[0]) : self::_a2($args);
			break;
		}
	}

	static function _a(array $attrs = []) {
		$attrs = array_merge([
			'href'  => '#',
			'text'  => '',
			'class' => false,
			'icon'  => false,
		], $attrs);
		$icon = $attrs['icon'] ? self::icon($attrs['icon']) : '';
		$class = $attrs['class'] ? " class=\"{$attrs['class']}\"" : '';
		return "<a href=\"{$attrs['href']}\"{$class}>{$icon}{$attrs['text']}</a>";
	}

	static function _a2($args) {
		return self::_a([
			'href' => take($args, 0),
			'text' => take($args, 1),
			'icon' => take($args, 2),
			'class' => take($args, 3),
		]);
	}

	static function icon($type) {
		return "<i class=\"icon-{$type}\"></i> ";
	}

	static function btn($href, $text, $type='', $icon) {
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
					break;
                case 'checked':
					if ($v) $html .=' checked="checked"'; break;
					break;
				case 'autocomplete': 
					if (!$v) $html .=' autocomplete="off"'; break;
					break;
                case 'required':
                    if ($v) $html .=' required'; break;
					break;
                case 'multiple':
                    if ($v) $html .=' multiple'; break;
					break;
				case 'readonly':
					if ($v) $html .=' readonly'; break;
					break;
				case 'autofocus':
					if ($v) $html.=' autofocus'; break;
					break;
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
		$table = new table($data);	
		foreach ($options as $k => $v)
			$table->{$k} = $v;
		return $table;
	}

	static function alert($content='', array $options=[]) {
		if (!isset($content{0})) return '';
		$options = array_merge([
			'type'   => 'info',
			'inline' => true,
			'closer' => true,
		], $options);
		$cls = 'alert'. ($options['inline'] ? '' : ' alert-block');
		$cls .= isset($options['type']{0}) ? " alert-{$options['type']}" : '';
		$html = "<div class=\"{$cls}\">";
		if ($options['closer'])
			$html .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
		$html .= "{$content}</div>";
		return $html;
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
