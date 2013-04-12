;(function() {
	var ws = new WebSocket("ws://173.255.209.99:7334");
	window.ws = ws;
	ws.onclose = function() {
		console.log('closed');
	};
	ws.onmessage = function(msg) {
		console.log(msg.data);	
	};
	ws.error = function(e) {
		console.log('error');
		console.log(e);	
	};
	ws.onopen = function() {
		console.log('socket opened');		
	};

	function init() {
	}

	$(init);
}());
