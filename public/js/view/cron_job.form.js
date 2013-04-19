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
		
		EL.form = $('form');
		EL.form.validate({
			errorElement: 'span',
			errorContainer: '.controls',
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
				el.text('OK!').addClass('valid');
				el.closest('.control-group')
					.removeClass('error')
					.addClass('success');
			}
		});
		//EL.form.submit(function() {
			//var data = EL.form.serialize();
			//$.post(EL.form.attr('action'), data, function(data) {
				//EL.form.closest('.row').replaceWith(data);
				//init();
			//});
			//return false;
		//});
		//EL.body = $(document.body);
		//EL.body.on('click', 'a', function(event) {
			//var el = $(event.currentTarget);
			//$.get(el.attr('href'), function(data) {
				//EL.form.closest('.row').replaceWith(data);
				//init();
			//});
			//return false;
		//});
	}

	$(init);
}(jQuery));
