(function(NS) {

	"use strict";

	var FORM_OPTIONS = {
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
	};

	function handleButtonStates(event) {
		var btn = $(event.currentTarget);
		btn.button('loading');
		setTimeout(function() {
			btn.button('reset');
		}, 750);
	}

	function handleForms() {
		if (!NS.ELEMENT.forms.length) return;
		
		if ($.fn.validate) {
			NS.ELEMENT.forms.each(function(n, form) {
				$(form).validate(FORM_OPTIONS);
			});
		}
	}

	function addEventListeners() {
		NS.ELEMENT.body.on('click', 'form button[type="submit"]', handleButtonStates);
		handleForms();
	}

	function init() {
		NS.ELEMENT.forms = $('form', NS.ELEMENT.body);
		addEventListeners();
	}

	$LAB.script('/js/validate.min.js').wait(init);
}(APP));
