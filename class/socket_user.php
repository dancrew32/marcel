<?
class socket_user {

	public $socket;
	public $id;
	public $user_id;
	public $headers = array();
	public $handshake = false;

	public $handlingPartialPacket = false;
	public $partialBuffer = "";

	public $sendingContinuous = false;
	public $partialMessage = "";

	public $hasSentClose = false;

	function __construct($id,$socket) {
		$this->id = $id;
		$this->socket = $socket;
	}
}
