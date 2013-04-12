<?
require_once(dirname(__FILE__).'/inc.php');

class echoServer extends socket_server {
	//protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.
	
	protected function process($user, $message) {
		//$url = file_get_contents($message);
		//preg_match('/<title>.+/', $url, $matches);
		$this->send($user, h($message));
	}
	
	protected function connected($user) {
		$this->stdout(print_r($user, true));
		// parse cookie, get user object
	}
	
	protected function closed($user) {
		// cleanup
	}
}

$echo = new echoServer("173.255.209.99","7334");
