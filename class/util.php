<?
class util {

	static function render($o, $p) {
		require_once CONTROLLER_DIR.'/'.$o['c'].'.php';
		$c = "controller_{$o['c']}";
		$obj = new $c($p);
		$obj->$o['m']($p);
		$_view = VIEW_DIR.'/'.$o['c'].'.'.$o['m'].'.php';
		if (!file_exists($_view)) return;
		if ($obj->skip) return $obj->skip = false;
		ob_start();
		extract((array)$obj);
		include $_view;
		return ob_get_clean();
	}

	static function is_ajax() {
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
	}

	static function file_up($file, $dir=false) {
		$dir = $dir ? $dir : UP_DIR;	
		$path = $dir .'/'. basename($file['name']);		
		return move_uploaded_file($file['tmp_name'], $path);
	}

	public static function explode_pop($delimiter, $str) {
		$delimiter = '/.*'. preg_quote($delimiter, '/') .'/';
		return preg_replace($delimiter, '', $str);
	}

	public static function explode_shift($delimiter, $str) {
		$delimiter = "/". preg_quote($delimiter, '/') .".*/";
		return preg_replace($delimiter, '', $str);
	}


	# recursive glob
	static function rglob($pattern='*', $flags = 0, $path='') {
		$paths = glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
		$files = glob($path . $pattern, $flags);
		foreach ($paths as $path) 
			$files = array_merge($files, self::rglob($pattern, $flags, $path));
		return $files;
	}

	static function starts_with($string, $start) {
		return substr($string, 0, strlen($start)) == $start;
	}

	static function list_english(array $items=[]) {
		$count = count($items);
		if (!$count) 
			return '';
		if ($count == 1)
			return $items[0];
		$last = $count - 1;
		return implode(', ', array_slice($items, 0, $last)) . ' and ' . $items[$last];
	}

	static function pluck($find, $key, $data) {
		foreach ($data as $k => $struct) {
			if ($find != take($struct, $key)) continue;
			return $struct;
		}
		return false;
	}

	static function pluck_key($find, $key, $data) {
		foreach ($data as $k => $struct) {
			if ($find != take($struct, $key)) continue;
			return $k;
		}
		return false;
	}

    static function array_sort(&$array, $order_by = array()) {
        if (count($array)) {
            $sortcols = array();
            foreach ($array as $key => $row)
                foreach ($order_by as $col => $direction)
                    $sortcols[$col][$key] = take($row, $col, null);

            foreach ($order_by as $col => $direction)
                $params[] = '$sortcols["'.$col.'"], SORT_'.(strtoupper($direction) == 'ASC' ? 'ASC' : 'DESC');

            $cmd = 'array_multisort('.implode(',', $params).', $array);';
            eval($cmd);
        }   
    }

    static function truncate($text, $max_chars, $preserve_words = false, $trailing_string = '...') {
        if (!isset($text{$max_chars}))
            return $text;
            
        $max_chars -= strlen($trailing_string); // text must include trailing_string at this point
            
        if (!$preserve_words)
            return substr($text, 0, $max_chars).$trailing_string;
            
        $arr = str_word_count($text, 2); 
        foreach ($arr as $offset => $value)
            if ($offset + strlen($value) > $max_chars)
                return trim(substr($text, 0, $offset)).$trailing_string;
                
        return $text;
    }  
}


