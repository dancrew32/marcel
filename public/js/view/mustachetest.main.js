(function(NS) {

	"use strict";

	function mustacheTest() {
		$.get('/mustache', function(data) {
			var html = NS.TEMPLATE.mustache_test.render(data);
			var el = $('#append-to-me');
			el.append(html);
			el.animate({
				scrollTop: el[0].scrollHeight
			}, 700);
		}, 'json');
	}

	function addEventListeners() {
		if (typeof Hogan !== 'undefined') {
			NS.TEMPLATE.mustache_test = Hogan.compile($('#mustachetest-template').html()); 
			mustacheTest();
			setInterval(mustacheTest, 1000);
		}
	}

	function init() {
		addEventListeners();
	}

	$(init);

}(APP));
