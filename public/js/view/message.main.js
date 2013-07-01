// WebSockets
(function(NS) {

	"use strict";

	var API = {};
	var EL = {};
	var EVENT = {
		chatReceived: 'foo:bar:response',
		sendChat: 'foo:bar'
	};
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
		NS.TEMPLATE.chatMessage = Hogan.compile($('#message-message').html());
		NS.SOCKETS.main.onmessage = onMessage;
		EL.chatForm.on('submit', chatSubmit);
		EL.chatForm.find('input:first').focus();
	}

	function websocketFocusRestore() {
		setTimeout(function() {
			websocketInit();	
		}, 500);
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
		EL.chatForm[0].reset();
		var ok = emit(EVENT.sendChat, { text: text });
		if (!ok)
			input.val(text);
	}

	function handleChat(event, data) {
		var html = NS.TEMPLATE.chatMessage.render({
			message: data.text,
			cls: data.cls
		});
		var el = $('#messages');
		el.append(html);
		el.animate({
			scrollTop: el[0].scrollHeight
		}, 700);
		//notifyDesktop('', 'new message', data.text);
	}

	//function detectOffline() {
		//if (navigator.onLine) return;	
		//alert('offline!');
	//}

	function addEventListeners() {
		if (API.chat) {
			NS.SOCKETS.main = $.gracefulWebSocket(API.chat, SOCKET_OPTIONS.chat);
			NS.SOCKETS.main.onopen = websocketInit;
			//setInterval(detectOffline, 2500);
		}
		NS.ELEMENT.win.on(EVENT.chatReceived, handleChat);
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
