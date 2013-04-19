(function($) {
	var EL = {};
	var TIMER = {};
	var TYPEAHEAD = {
		labels: [],
		mapped: {}
	};

	function getScript(query, process) {
		return $.post(EL.cronScript.data('api'), { 
			query: query 
		}, function(data) {
			TYPEAHEAD.labels = [];
			TYPEAHEAD.mapped = {};
			$.each(data, function(i, item) {
				TYPEAHEAD.mapped[item.v] = item.k;	
				TYPEAHEAD.labels.push(item.v);
			});
			process(TYPEAHEAD.labels);
		}, 'json')
	}

	function init() {
		EL.cronScript = $('input.cron-script');
		EL.cronScript.typeahead({
			source: function(query, process) {
				if (TIMER.getScript) clearTimeout(TIMER.getScript);
				TIMER.getScript = setTimeout(function() {
					TYPEAHEAD.labels = [];
					TYPEAHEAD.mapped = {};
					return getScript(query, process);
				}, 400);
			},
			updater: function(item) {
				return TYPEAHEAD.mapped[item];
			}
		});
	}

	$(init);
}(jQuery));
