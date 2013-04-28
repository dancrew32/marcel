// Main 
var APP = {
	CLASSES: {},
	ELEMENT: {}
};

(function(NS) {

	"use strict";

	function handleButtonStates(event) {
		var btn = $(event.currentTarget);
		btn.button('loading');
		setTimeout(function() {
			btn.button('reset');
		}, 750);
	}

	function addEventListeners() {
		NS.ELEMENT.body.on('click', 'form button[type="submit"]', handleButtonStates);
	}

	function init() {
		NS.ELEMENT.win = $(window);	
		NS.ELEMENT.body = $(document.body);	
		addEventListeners();
	}

	$(init);

}(APP));
