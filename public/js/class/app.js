// Main 
var APP = {
	CLASSES: {}, // Module Namespace
	ELEMENT: {}, // jQuery Elements
	SOCKETS: {}, // WebSockets
	TEMPLATE: {} // Mustache templates
};

(function(NS) {

	"use strict";

	function addEventListeners() {
	}

	function init() {
		NS.ELEMENT.win   = $(window);
		NS.ELEMENT.body  = $(document.body);
		addEventListeners();
	}

	$(init);

}(APP));
