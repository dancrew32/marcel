// Main 
var APP = {
	CLASSES: {},
	ELEMENT: {}
};

;(function(NS) {

	"use strict";

	function init() {
		NS.ELEMENT.win = $(window);	
		NS.ELEMENT.body = $(document.body);	
	}

	$(init);

}(APP));
