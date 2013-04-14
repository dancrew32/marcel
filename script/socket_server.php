<?
require_once(dirname(__FILE__).'/inc.php');

class echoServer extends socket_server {
	protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.
	
	protected function process($user, $message) {
		$this->stdout(print_r($message, true));
		$this->send($user, utf8_encode($message));
		//foreach ($this->users as $u) {
			//$this->send($u, $message);
		//}
	}
	
	protected function connected($user) {
		$this->stdout(print_r($user, true));
		// parse cookie, get user object
	}
	
	protected function closed($user) {
		$this->stdout(print_r($user, true));
		// cleanup
	}
}

$echo = new echoServer("173.255.209.99","7334");
