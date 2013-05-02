// Main 
var APP = {
	CLASSES: {},
	ELEMENT: {},
	SOCKETS: {}
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
