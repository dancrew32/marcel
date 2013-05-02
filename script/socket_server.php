<?
require_once(dirname(__FILE__).'/inc.php');

class echoServer extends socket_server {
	protected $maxBufferSize = size::ONE_MB;
	
	protected function process($user, $message) {
		# Message looks like "client:thing||<JSON>"
		if (strpos($message, '||') === false) return;

		$msgparts = explode('||', $message);
		$event    = array_shift($msgparts);
		$data     = json_decode(implode('', $msgparts));

		//$this->stdout(print_r($user->user, true));

		switch ($event) {
			case 'foo:bar':	
				$this->event_foo_bar($user, $data);
				break;
		}
	}
	
	protected function connected($user) {
		# Match socket_user to actual user
		$cookies = explode(';', take($user->headers, 'cookie', []));
		$session_id = false;
		foreach ($cookies as $c) {
			$cparts = explode('=', trim($c));	
			if ($cparts[0] == SESSION_NAME)
				$session_id = $cparts[1];
		}

		# Apply user to socket user
		if ($session_id) {
			$session = Session::find_by_id($session_id);
			$session_data = Session::unserialize($session->data);
			if (take($session_data, 'in') == 1) {
				$user_id = take($session_data, 'id');
				if ($user_id) {
					$cache_key  = User::cache_key($user_id);
					$user->user = cache::get($cache_key, $found, true);
					if (!$found) 
						$user->user = User::find_by_id($user_id);
				}
			}
		}
		//$this->stdout(print_r($user, true));
	}
	
	protected function closed($user) {
		$user->destroy();
		//$this->stdout(print_r($user, true));
	}


/*
 * EVENTS
 */
	function event_foo_bar($user, $data) {
		if (!auth::admin($user->user)) return;
		$foo = take($data, 'foo', 'cheese');
		$this->send_all("I'm {$user->user->full_name()} and I like {$foo}.");
	}
}

$echo = new echoServer("173.255.209.99","7334");
