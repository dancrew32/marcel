<?
class Session extends model {
	static $table_name = 'sessions';

    static function open() {
        //delete old session handlers
        $limit = time() - (3600 * 24);
		$s = Session::all(['conditions' => "timestamp < {$limit}"]);
		if (count($s)) {
			foreach($s as $sesh)
				$sesh->delete();
		}
		return true;
    }

	static function close() {
		return true;
	}

    static function read($id) {
		$result = Session::find_by_id($id);
        if ($result)
			return $result->data;
		return false;
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
}
