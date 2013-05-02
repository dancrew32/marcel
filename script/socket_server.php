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


		switch ($event) {
			case 'foo:bar':	
				$this->event_foo_bar($user, $data);
				break;
		}

		//$this->stdout(print_r($user->user, true));
	}
	
	protected function connected($user) {
		# Match socket_user to actual user
		$session_id = $user->get_session_id();

		# Apply user to socket user
		$user->try_set_user($session_id);

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
		$foo = h(take($data, 'foo', 'cheese'));
		$this->send_all("I'm {$user->user->full_name()} and I like {$foo}.");
	}
}

$echo = new echoServer("173.255.209.99","7334");
