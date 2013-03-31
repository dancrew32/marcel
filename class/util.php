<?
class util {
	static function is_ajax() {
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
	}

	static function file_up($file, $dir=false) {
		$dir = $dir ? $dir : UP_DIR;	
		$path = $dir .'/'. basename($file['name']);		
		return move_uploaded_file($file['tmp_name'], $path);
	}

	static function explode_pop($arr, $split='/') {
		$a = explode($split, $arr);
		return end($a);
	}

	# recursive glob
	static function rglob($pattern='*', $flags = 0, $path='') {
		$paths = glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
		$files = glob($path . $pattern, $flags);
		foreach ($paths as $path) 
			$files = array_merge($files,self::rglob($pattern, $flags, $path));
		return $files;
	}

	static function starts_with($string, $start) {
		return (substr($string, 0, strlen($start)) == $start);
	}

	static function pluck($find, $key, $data) {
		foreach($data as $struct) {
			if ($find != $struct->$key) continue;
			return $struct;
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


