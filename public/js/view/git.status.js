(function() {

	function addEventListeners() {
		$('#git-status-modified .git-file').popover({
			html: true
		});	
	}
	
	function init() {
		addEventListeners();
	}

	$(init);
}());
