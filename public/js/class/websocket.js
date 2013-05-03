// WebSockets
(function(NS) {

	"use strict";

	var API = {};
	var EL = {};
	var NOTIFY = false;

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
		//console.log('sockets are up');
		NS.SOCKETS.main.onmessage = onMessage;
		EL.chatForm.on('submit', chatSubmit);
	}
	
	function notifyDesktop(image, a, text) {
		//if (!NOTIFY) return;
		//EL.notificationsEnable.hide();
		//console.log('here');
	}

	function enableNotifications(event) {
		if (window.webkitNotifications.checkPermission() == 0) {
			window.webkitNotifications.createNotification(
				'http://i.stack.imgur.com/dmHl0.png', 
				'wat', 
				'okay'
			).show();
		} else {
			window.webkitNotifications.requestPermission();
		}
	}

	function onMessage(event) {
		var e = $.parseJSON(event.data);
		NS.ELEMENT.win.trigger(e.event, e);
	}

	function emit(name, data) {
		var json = JSON.stringify(data);
		name = name + '||';
		return NS.SOCKETS.main.send(name + json);
	}

	function chatSubmit(event) {
		event.preventDefault();
		var input = $('input:first', EL.chatForm);
		var text = input.val();
		input.val('');
		var ok = emit('foo:bar', { text: text });
		if (!ok)
			input.val(text);
	}

	function handleChat(event, data) {
		$('#messages').append("<p>"+ data.text +"</p>");
		//notifyDesktop('', 'new message', data.text);
	}

	function addEventListeners() {
		if (API.chat) {
			NS.SOCKETS.main = $.gracefulWebSocket(API.chat, SOCKET_OPTIONS.chat);
			NS.SOCKETS.main.onopen = websocketInit;
		}
		NS.ELEMENT.win.on('foo:bar:response', handleChat);
		//EL.notificationsEnable.on('click', enableNotifications);
	}

	function init() {
		EL.notificationsEnable = $('#notifications-enable');
		EL.chatForm = $('#chat-form');
		API.chat = EL.chatForm.data('chat-api');

		addEventListeners();
	}

	$(init);

}(APP));
