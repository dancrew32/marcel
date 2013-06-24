(function() {

	var EL = {};

	function cancel(e) {
		e.stopPropagation();
	}

	function addEventListeners() {
		$('a', EL.context).click(cancel);
		$('.git-file', EL.context).popover({ html: true });	
	}
	
	function init() {
		EL.context = $('#git-status-modified');
		addEventListeners();
	}

	$(init);
}());
