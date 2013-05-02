// WebSockets
(function(NS) {

	"use strict";

	function websocketInit(event) {
		console.log('sockets are up');
		NS.SOCKETS.main.onmessage = onMessage;
		window.emit = emit;
	}

	function onMessage(event) {
		var data = event.data;	
		$('.hero-unit').append("<div>"+ data +"</div>")	
	}

	function emit(name, data) {
		var json = JSON.stringify(data);
		name = name + '||';
		NS.SOCKETS.main.send(name + json);
	}

	function init() {
		NS.SOCKETS.main = $.gracefulWebSocket("ws://l.danmasq.com:7334", {
			fallbackPollParams: {
				//"latestMessageID": function () {
					//return latestMessageID;
				//},
				fallback: false,
				keepAlive: true
			} 
		});
		NS.SOCKETS.main.onopen = websocketInit;
	}

	$(init);

}(APP));
