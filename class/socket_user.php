<?
class socket_user {

	public $socket;
	public $id;
	public $user = null; # reserved for User::$user
	public $headers = array();
	public $handshake = false;

	public $handlingPartialPacket = false;
	public $partialBuffer = "";

	public $sendingContinuous = false;
	public $partialMessage = "";

	public $hasSentClose = false;

	function __construct($id, $socket) {
		$this->id = $id;
		$this->socket = $socket;
	}

	function get_session_id() {
		$cookies = explode(';', take($this->headers, 'cookie', []));
		$session_id = false;
		foreach ($cookies as $c) {
			$cparts = explode('=', trim($c));	
			if ($cparts[0] == SESSION_NAME)
				$session_id = $cparts[1];
		}
		return $session_id;
	}

	function try_set_user($session_id) {
		if (!$session_id) return false;
		$session = Session::find_by_id($session_id);
		$session_data = Session::unserialize($session->data);
		if (take($session_data, 'in') != 1) return false;
		$user_id = take($session_data, 'id');
		if (!$user_id) return;

		$cache_key  = User::cache_key($user_id);
		$this->user = cache::get($cache_key, $found, true);
		if ($found) return;
		$this->user = User::find_by_id($user_id);
	}

	function destroy() {
		$this->user = null;
	}
}
