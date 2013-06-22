(function(NS) {

	"use strict";

	var TIMER = {};
	var TYPEAHEAD = {
		labels: [],
		mapped: {}
	};

	var EXCEPTION = {
		dataApi: 'Missing data-api attribute'		
	};

	function handleButtonStates(event) {
		var btn = $(event.currentTarget);
		btn.button('loading');
		setTimeout(function() {
			btn.button('reset');
		}, 750);
	}

	function typeaheadQuery(context, query, process) {
		var el  = context.$element;
		var api = el.data('api');
		if (!api) return $.error(EXCEPTION.dataApi);

		var type = el.data('typeahead-method');
		return $.post(api, { 
			query: query 
		}, function(data) {
			resetTypeaheadData();
			$.each(data, function(n, item) { setTypeaheadData(n, item, type); });
			process(TYPEAHEAD.labels);
		}, 'json')
	}

	function resetTypeaheadData() {
		TYPEAHEAD.labels = [];
		TYPEAHEAD.mapped = {};
	}

	function setTypeaheadData(i, item, type) {
		switch (type) {
			case 'key-get-value':
				TYPEAHEAD.mapped[item.v] = item.k;	
				TYPEAHEAD.labels.push(item.v);
			break;
			default:
				TYPEAHEAD.labels.push(item);
		}
	}

	function handleUpdater(item) {
		var type = this.$element.data('typeahead-method');
		switch (type) {
			case 'key-get-value':
				return TYPEAHEAD.mapped[item];
			default:
				return item;
		}
	}

	function handleSource(query, process) {
		if (TIMER.getScript) clearTimeout(TIMER.getScript);
		var self = this;
		TIMER.getScript = setTimeout(function() {
			resetTypeaheadData();
			return typeaheadQuery(self, query, process);
		}, 400);
	}
	
	function handleTypeaheads() {
		NS.ELEMENT.typeaheads.typeahead({
			//minLength: $(this).data('minlength'),
			source: handleSource,
			matcher: function() { return true; },
			updater: handleUpdater
			//highlighter: handleHighlighter
		});
	}

	function addEventListeners() {
		handleTypeaheads();
	}

	function init() {
		NS.ELEMENT.typeaheads = $('input.typeahead[data-api]', NS.body);
		addEventListeners();
	}

	$(init);

}(APP));
