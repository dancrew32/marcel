<?
require_once(dirname(__FILE__).'/inc.php');

class chat_server extends socket_server {
	
	static $text_class = [
		'muted'   => 'muted',
		'info'    => 'text-info',
		'success' => 'text-success',
		'warning' => 'text-warning',
		'error'   => 'text-error',
	];

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
		$data = $this->get_user_total_data($user);
		$this->send_all(json_encode($data));

		//$this->stdout(print_r($user, true));
	}


/*
 * EVENTS
 */
	function event_connect($user) {
		$name = $user->user ? $user->user->full_name() : 'Anonymous';
		$data = [
			'event' => 'foo:bar:response',
			'text'  => "{$name}: Joined the room.",
			'cls'   => self::$text_class['muted'],
		];

		$this->send_all(json_encode($data));

		$data = $this->get_user_total_data($user);
		$this->send($user, json_encode($data));
	}

	function get_user_total_data($user) {
		$user_count = $this->user_count() - 1;
		if ($user_count) {
			$user_list = [];
			foreach ($this->users as $u)
				$user_list[] = $u->full_name();

			$user_list = util::list_english($user_list);

			$user_suffix = $user_count == 1 ? 'person' : 'people';
			$text = "Looks like there's {$user_count} {$user_suffix} here ({$user_list}).";
		} else {
			$text = "Looks like you're the only one here.";
		}
		return [
			'event' => 'foo:bar:response',
			'text'  => $text,
			'cls'   => self::$text_class['muted'],
		];
	}

	function event_disconnect($user) {
		$data = [
			'event' => 'foo:bar:response',
			'text' => "{$user->full_name()}: Left the room.",
		];

		$this->send_all(json_encode($data));
	}

	function event_foo_bar($user, $data) {
		$text = h(take($data, 'text'));
		$text_trimmed = trim($text);
		if (!isset($text_trimmed{0})) return false;


		# Tags
		$tag_subs = [
			'/(#\w+)/' => '<a href="https://twitter.com/search?q=$1&src=hash">$1</a>',
			'/(@\w+)/' => '<code>$1</code>',
		];
		$text = preg_replace(array_keys($tag_subs), array_values($tag_subs), $text); 

		# Image
		if (auth::admin($user->user)) {
			$img_regex = '/(https?:\/\/.*\.(?:png|jpg))/i';
			if (preg_match($img_regex, $text, $match)) {
				$text .= "<br><div class=\"thumbnail\">";
				$text .= image::get([
					'src' => $match[1],
					'w' => 720,
					'h' => 405,
					'q' => 10,
				], true, 'my image');
				$text .= "</div>";
			}
		}

		$data = [
			'event' => 'foo:bar:response',
			'text' => "<strong>{$user->full_name()}</strong>: {$text}",
		];

		$this->send_all(json_encode($data), [
			'sender' => $user,
			'sender_message' => json_encode(array_merge([
				'cls' => self::$text_class['success'],
			], $data)),
		]);
	}

}

$echo = new chat_server("173.255.209.99","7334");
