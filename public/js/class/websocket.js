// WebSockets
(function(NS) {

	"use strict";

	var API = {};
	var EL = {};

	var SOCKET_OPTIONS = {
		chat: {
			fallbackPollParams: {
				//"latestMessageID": function () {
					//return latestMessageID;
				//},
				fallback: false,
				keepAlive: true
			} 
		}
	};

	function websocketInit(event) {
		console.log('sockets are up');
		NS.SOCKETS.main.onmessage = onMessage;
		EL.chatForm.on('submit', chatSubmit);
	}

	function onMessage(event) {
		var e = $.parseJSON(event.data);
		NS.ELEMENT.win.trigger(e.event, e);
	}

	function emit(name, data) {
		var json = JSON.stringify(data);
		name = name + '||';
		NS.SOCKETS.main.send(name + json);
	}

	function chatSubmit(event) {
		event.preventDefault();
		var input = $('input:first', EL.chatForm);
		var text = input.val();
		emit('foo:bar', { text: text });
		input.val('');
	}

	function handleChat(event, data) {
		$('#messages').append("<p>"+ data.text +"</p>")	
	}

	function addEventListeners() {
		if (API.chat) {
			NS.SOCKETS.main = $.gracefulWebSocket(API.chat, SOCKET_OPTIONS.chat);
			NS.SOCKETS.main.onopen = websocketInit;
		}
		NS.ELEMENT.win.on('foo:bar:response', handleChat);
	}

	function init() {
		EL.chatForm = $('#chat-form');
		API.chat = EL.chatForm.data('chat-api');

		addEventListeners();
	}

	$(init);

}(APP));
