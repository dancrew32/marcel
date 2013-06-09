<?
class controller_message extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Message Home');
		parent::__construct($o);
   	}
	
	function main($o) {
	}

	function message() {
		$this->message = 'Connecting...';		
		$this->cls = 'muted';
	}

	function chat_form() {
		$this->form = new form;
		$this->form->open('#', 'post', [
			'id' => 'chat-form',
			'data-chat-api' => 'ws://l.danmasq.com:7334',
		]);
		$this->_build_chat_form();
		$this->form->add(new field('submit', [
			'text' => "Message",
		]));
		echo $this->form;
	}

	private function _build_chat_form() {
		$chat_field = new field('input', [ 
			'name'         => 'chat', 
			'class'        => 'input-block-level',
			'placeholder'  => 'Type anything!',
			'autocomplete' => false,
		]);
		$this->form
			->group($chat_field);
	}
}
