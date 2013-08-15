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
		$('#my-tip').tooltip({ selector: 'a' });
		$('#popit button').popover();
		if ($.fn.unveil)
			$('img.unveilable').unveil(100, function(img) {
				$(img).addClass('unveiled');
			});
		if (FastClick)
			FastClick.attach(document.body);
	}

	function init() {

		NS.ELEMENT.win   = $(window);
		NS.ELEMENT.body  = $(document.body);
		addEventListeners();
	}

	$LAB.script('/js/fastclick.js').wait(init);

}(window.APP));
