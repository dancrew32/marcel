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
		}, 500);
	}

	function addEventListeners() {
		NS.ELEMENT.body.on('click', 'button', handleButtonStates);
	}

	function init() {
		NS.ELEMENT.win = $(window);	
		NS.ELEMENT.body = $(document.body);	
		addEventListeners();
	}

	$(init);

}(APP));
