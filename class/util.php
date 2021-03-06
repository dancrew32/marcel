<?
class util {

	static function render($o, $p) {
		$config = config::$setting;
		require_once "{$config['controller_dir']}/{$o['c']}.php";
		$c = "controller_{$o['c']}";
		$obj = new $c($p);
		$obj->$o['m']($p);
		if ($obj->skip) return $obj->skip = false;

		$path = "{$config['view_dir']}/{$o['c']}.{$o['m']}";

		# PHP
		$_view = "{$path}.php";
		if (is_file($_view)) {
			ob_start();
			extract((array)$obj);
			include $_view;
			return ob_get_clean();
		}

		# Mustache
		$_view = "{$path}.mustache";
		if (is_file($_view)) {
			return stache::render($_view, (array)$obj);
		}

		# Markdown
		$_view = "{$path}.md";
		if (is_file($_view)) {
			return markdown::render($_view, (array)$obj);
		}
	}

	static function file_up($file, $dir=false) {
		$path = $dir .'/'. basename($file['name']);		
		return move_uploaded_file($file['tmp_name'], $path);
	}

	static function explode_pop($delimiter, $str) {
		$delimiter = '/.*'. preg_quote($delimiter, '/') .'/';
		return preg_replace($delimiter, '', $str);
	}

	static function explode_shift($delimiter, $str) {
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

	static function find_files($search='', $fuzzy=25) {
		$root_dir = config::$setting['root_dir'];
		$it = new RecursiveDirectoryIterator($root_dir);
		$out = [];
		$ignore = ['/tmp'];
		foreach(new RecursiveIteratorIterator($it) as $file) {
			$file = (str_replace($root_dir, '', (string) $file));
			foreach ($ignore as $ig) {
				if (self::starts_with($file, $ig)) {
					continue 2;
					break;
				}
			}
			$name = self::explode_pop('/', $file);
			if ($name == '.' || $name == '..') continue;
			similar_text($search, $file, $match);
			if ($match <= $fuzzy) continue;
			$out[] = $file;
		}
		return $out;
	}

	static function directory_list($directory, $parent=false) {
		$root_dir = config::$setting['root_dir'];
		$forbidden_files = '#(api\.php$|index\.php$)#';
		$dir = rtrim($root_dir.$directory, '/');
		$pattern = "{$dir}/{*,.*}";
		$dir = glob($pattern, GLOB_BRACE|GLOB_NOSORT);
		$count = $parent ? 1 : 2;
		return array_filter($dir, function($hit) use($count, $forbidden_files) {
			$end = util::explode_pop('/', $hit);
			$out = !preg_match("#^\.{1,$count}$#", $end);
			if (!$out) return $out;
			$out = !preg_match($forbidden_files, $end);
			return $out;
		});
	}

	static function starts_with($string, $start) {
		return substr($string, 0, strlen($start)) == $start;
	}

	static function ends_with($string, $end) {
		$end_len = strlen($end);
		return (substr($string, strlen($string) - $end_len, $end_len) == $end);
	}

	static function array_flatten($array) {
		$arrayValues = [];
		foreach ($array as $value) {
			if (is_scalar($value) || is_resource($value))
				$arrayValues[] = $value;
			elseif (is_array($value))
				$arrayValues = array_merge($arrayValues, util::array_flatten($value));
		}
		return $arrayValues;
	}

	static function list_english($items=[]) {
		$items = (array) $items;
		$items = self::array_flatten($items);
		$count = count($items);
		if (!$count) 
			return '';
		if ($count == 1)
			return $items[0];
		$last = $count - 1;
		$items = array_map('strtolower', $items);
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
        if (!count($array)) return false;
		$sortcols = array();
		foreach ($array as $key => $row)
			foreach ($order_by as $col => $direction)
				$sortcols[$col][$key] = take($row, $col, null);

		foreach ($order_by as $col => $direction)
			$params[] = '$sortcols["'.$col.'"], SORT_'.(strtoupper($direction) == 'ASC' ? 'ASC' : 'DESC');

		$cmd = 'array_multisort('.implode(',', $params).', $array);';
		eval($cmd);
    }

	static function to_snake($str) {
		return preg_replace_callback('/[A-Z]/', create_function('$match', 'return "_" . strtolower($match[0]);'),  $str);  
	}

	static function to_title($str) {
		return implode('', array_map('ucwords', explode('_', $str)));
	}

	static function to_camel($str) {
		$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));  
		return strtolower(substr($str, 0, 1)) . substr($str, 1);  
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

	static function ordinal_suffix($num) {
		if ($num < 11 || $num > 13) {
			 switch($num % 10){
				case 1: return 'st';
				case 2: return 'nd';
				case 3: return 'rd';
			}
		}
		return 'th';
	}

	static function best_array_match($search, $array) {
		$percent = 0;
		$out = false;
		foreach ($array as $value) {
			similar_text($search, $value, $match);
			if ($match <= $percent) continue;
			$out = $value;
		}
		return $out;
	}


	# DELETE LINE with MATCH
	static function delete_line_with_match($file, $string, $lines_after=0) {
		$i = 0;
		$temp = array();

		$read = fopen($file, "r") or die("can't open the file"); 
		while(!feof($read)) {
			$temp[$i] = fgets($read);	
			++$i;
		}
		fclose($read);

		$next = 0;
		$write = fopen($file, "w") or die("can't open the file");
		foreach($temp as $a) {
			if (!$next && !strstr($a, $string))
				fwrite($write, $a);
			else
				$next = $lines_after--;
		}
		fclose($write);
	}

	# REPLACE LINE with MATCH
	static function replace_line_with_match($file, $string, $replacement='') {
		$i = 0;
		$temp = array();

		$read = fopen($file, "r") or die("can't open the file");
		while(!feof($read)) {
			$temp[$i] = fgets($read);	
			++$i;
		}
		fclose($read);

		$write = fopen($file, "w") or die("can't open the file");
		foreach($temp as $a)
			if (!strstr($a,$string)) 
				fwrite($write, $a);
			else
				fwrite($write, $replacement);
		fclose($write);
	}

	static function filter($value, $filter) {
		require_once config::$setting['vendor_dir'] .'/filterus/vendor/autoload.php';
		return \Filterus\Filter::factory($filter)->filter($value);
	}

	static function strclean($str) {
		return trim(preg_replace('/\t+/', '', $str));
	}

	static function unlink_if($file) {
		if (file_exists($file))
			unlink($file);
	}

	static function fix_word_quotes($str) {
		$str = mb_convert_encoding($str, 'HTML-ENTITIES', 'UTF-8');
		$chars = [
			'&lsquo;' => "'", //Left single quote
			'&rsquo;' => "'", //Right single quote
			'&ldquo;' => '"', //Left double quote
			'&rdquo;' => '"', //Right double quote
			'&hellip;' => ' . . . ', //Ellipsis (three continuation dots...)
		];
		return trim(strtr($str, $chars));
	}

}
