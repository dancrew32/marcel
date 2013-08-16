<?
class Session extends model {
	static $table_name = 'sessions';

	static function init() {
		$cls = __CLASS__;
		session_set_save_handler(
			"{$cls}::open",
			"{$cls}::close",
			"{$cls}::read",
			"{$cls}::write",
			"{$cls}::destroy",
			"{$cls}::gc"
		);
		session_name(config::$setting['session_name']);
		session_start();
		register_shutdown_function('session_write_close');	
	}

	static function open() {
		//delete old session handlers
		$limit = time() - (3600 * 24);
		if (rand(0, 25)) 
			return true;
		$conditions = ['conditions' => "timestamp < {$limit}"];
		$s = (int) Session::count($conditions);
		if ($s)
			foreach(Session::all($conditions) as $sesh)
				$sesh->delete();
		return true;
	}

	static function close() {
		return true;
	}

	static function read($id) {
		try {
			$result = Session::find_by_id($id, [ 'select' => 'data' ]);
		} catch (Exception $e) {
			return _500(); # would be first DB section to fail
		}
		return $result ? $result->data : false;
	}

	static function write($id, $data) {
		$s = Session::find_by_id($id);
		if (!$s) {
			$s = new Session;
			$s->id = $id;
		}
		$s->data = $data;
		$s->timestamp = time();
		$s->save();
		return true;
	}

	static function destroy($id) {
		$s = Session::find_by_id($id);
		return $s->delete();
	}

	static function queue_delete_user_by_id($id) {
		return Worker::add([
			'class' => __CLASS__,
			'method' => 'delete_by_user_id',
			'args' => [
				'id' => $id,
			],
		]);
	}

	# queued to avoid table lock
	static function delete_by_user_id(array $args) {
		$id = take($args, 'id');
		return Session::delete_all([
			'conditions' => [ "data is not null and data like '%id|i:{$id}%'" ],
		]);
	}



	/**
	 * Garbage Collector
	 * @param int life time (sec.)
	 * @return bool
	 * @see session.gc_divisor      100
	 * @see session.gc_maxlifetime 1440
	 * @see session.gc_probability    1
	 * @usage execution rate 1/100
	 *        (session.gc_probability/session.gc_divisor)
	 */
	static function gc($max) {
		$time = time() - intval($max);
		$s = Session::all(['conditions' => "timestamp < {$time}"]);
		return $s->delete();
	}


    public static function unserialize($session_data) {
        $method = ini_get("session.serialize_handler");
        switch ($method) {
            case "php":
                return self::unserialize_php($session_data);
                break;
            case "php_binary":
                return self::unserialize_phpbinary($session_data);
                break;
            default:
                throw new Exception("Unsupported session.serialize_handler: " . $method . ". Supported: php, php_binary");
        }
    }

    private static function unserialize_php($session_data) {
        $return_data = array();
        $offset = 0;
        while ($offset < strlen($session_data)) {
            if (!strstr(substr($session_data, $offset), "|"))
                throw new Exception("invalid data, remaining: " . substr($session_data, $offset));
            $pos = strpos($session_data, "|", $offset);
            $num = $pos - $offset;
            $varname = substr($session_data, $offset, $num);
            $offset += $num + 1;
            $data = unserialize(substr($session_data, $offset));
            $return_data[$varname] = $data;
            $offset += strlen(serialize($data));
        }
        return $return_data;
    }
	
}
