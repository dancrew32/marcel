// Main 
var APP = {
	CLASSES: {},
	ELEMENT: {}
};

(function(NS) {

	"use strict";

	var TIMER = {};
	var TYPEAHEAD = {
		labels: [],
		mapped: {}
	};

	function handleButtonStates(event) {
		var btn = $(event.currentTarget);
		btn.button('loading');
		setTimeout(function() {
			btn.button('reset');
		}, 750);
	}

	function typeaheadQuery(context, query, process) {
		var el = context.$element;
		// if no data('api'), remove self from NS.ELEMENT.typeaheads
		var type = el.data('typeahead-method');
		return $.post(el.data('api'), { 
			query: query 
		}, function(data) {
			TYPEAHEAD.labels = [];
			TYPEAHEAD.mapped = {};
			$.each(data, function(i, item) {
				switch (type) {
					case 'key-get-value':
						TYPEAHEAD.mapped[item.v] = item.k;	
						TYPEAHEAD.labels.push(item.v);
					break;
					default:
						TYPEAHEAD.labels.push(item);
				}
			});
			process(TYPEAHEAD.labels);
		}, 'json')
	}
	
	function handleTypeaheads() {
		NS.ELEMENT.typeaheads.typeahead({
			source: function(query, process) {
				if (TIMER.getScript) clearTimeout(TIMER.getScript);
				var self = this;
				TIMER.getScript = setTimeout(function() {
					TYPEAHEAD.labels = [];
					TYPEAHEAD.mapped = {};
					return typeaheadQuery(self, query, process);
				}, 400);
			},
			matcher: function() { return true; },
			updater: function(item) {
				var type = this.$element.data('typeahead-method');
				switch (type) {
					case 'key-get-value':
						return TYPEAHEAD.mapped[item];
					default:
						return item;
				}
			},
			//highlighter: function(item){
				//var p = users[ item ];
				//var itm = ''
				//+ "<div class='typeahead_wrapper'>"
				//+ "<img class='typeahead_photo' src='" + p.photo + "' />"
				//+ "<div class='typeahead_labels'>"
				//+ "<div class='typeahead_primary'>" + p.name + "</div>"
				//+ "<div class='typeahead_secondary'>" + p.dept + "</div>"
				//+ "</div>"
				//+ "</div>";
				//return itm;
			//}	
		});
	}

	function handleForms() {
		NS.ELEMENT.forms.validate({
			errorElement: 'span',
			errorPlacement: function(error, el) {
				el = $(el);
				var help = el.parent().find('.help-block, .help-inline');
				if (help.length) {
					help.html(error);
				} else {
					help = $('<p class="help-block"></p>').append(error);
					el.after(help);		
				}
			},
			highlight: function(el) {
				$(el).closest('.control-group')
					.removeClass('success')
					.addClass('error');	
			},
			success: function(el) {
				el = $(el);
				//el.text('OK!').addClass('valid');
				el.closest('.control-group')
					.removeClass('error')
					.addClass('success');
			}
		});
	}

	function addEventListeners() {
		NS.ELEMENT.body.on('click', 'form button[type="submit"]', handleButtonStates);
		handleTypeaheads();
		handleForms();
	}

	function init() {
		NS.ELEMENT.win = $(window);	
		NS.ELEMENT.body = $(document.body);	
		NS.ELEMENT.typeaheads = $('input.typeahead', NS.body);
		NS.ELEMENT.forms = $('form', NS.body);
		addEventListeners();
	}

	$(init);

}(APP));
