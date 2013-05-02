<?
require_once(dirname(__FILE__).'/inc.php');

class chat_server extends socket_server {
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

		$this->event_connect($user);
		//$this->stdout(print_r($user, true));
	}
	
	protected function closed($user) {
		$this->event_disconnect($user);
		$user->destroy();
		//$this->stdout(print_r($user, true));
	}


/*
 * EVENTS
 */
	function event_connect($user) {
		$name = $user->user ? $user->user->full_name() : 'Anonymous';
		$data = [
			'event' => 'foo:bar:response',
			'text' => "{$name}: Joined the room.",
		];

		$this->send_all(json_encode($data));
	}

	function event_disconnect($user) {
		$name = $user->user ? $user->user->full_name() : 'Anonymous';
		$data = [
			'event' => 'foo:bar:response',
			'text' => "{$name}: Left the room.",
		];

		$this->send_all(json_encode($data));
	}

	function event_foo_bar($user, $data) {
		//if (!auth::admin($user->user)) return;
		$text = h(take($data, 'text'));
		$text_trimmed = trim($text);
		if (!isset($text_trimmed{0})) return false;

		$name = $user->user ? $user->user->full_name() : 'Anonymous';

		$data = [
			'event' => 'foo:bar:response',
			'text' => "{$name}: {$text}",
		];

		$this->send_all(json_encode($data));
	}
}

$echo = new chat_server("173.255.209.99","7334");
