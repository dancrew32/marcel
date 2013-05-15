// Main 
(function(global) {
	global.APP = {
		CLASSES: {}, // Module Namespace
		ELEMENT: {}, // jQuery Elements
		SOCKETS: {}, // WebSockets
		TEMPLATE: {} // Mustache templates
	};
}(window));

(function(NS) {

	"use strict";

	function addEventListeners() {
		if ($.wysiwyg)
			$('#editor-1').wysiwyg();
	}

	function init() {

		NS.ELEMENT.win   = $(window);
		NS.ELEMENT.body  = $(document.body);
		addEventListeners();
	}

	$(init);

}(window.APP));
