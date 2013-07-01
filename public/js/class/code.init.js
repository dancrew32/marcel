(function($) {
	var AREAS = [];
	var EDITORS = [];
	var OPTIONS = {
		lineNumbers: true,	
		keyMap: 'vim',
		showCursorWhenSelecting: true
	};

	function save(editor) {
		var $ta = $(editor.getTextArea());
		$ta[0].innerHTML = editor.getValue();

		var form = $ta.closest('form');
		$.post(form.attr('action'), form.serialize(), saveResponse, 'json');
	}

	function saveResponse(json) {
		console.log(json);
	}

	function codeInit() {
		AREAS.each(function(n, textarea) {
			$ta = $(textarea);
			var o = $.extend({
				mode: $ta.data('ext')
			}, OPTIONS);
			EDITORS.push(CodeMirror.fromTextArea(textarea, o));
		});
		CodeMirror.commands.save = save;
	}

	function init() {
		AREAS = $('textarea.code');
			
		if (!AREAS.length) return;
		$LAB.script('/js/class/code.js')
			.script('/js/class/code.search.cursor.js')
			.script('/js/class/code.dialog.js')
			.script('/js/class/code.mode.clike.js')
			.script('/js/class/code.mode.php.js')
			.script('/js/class/code.mode.xml.js')
			.script('/js/class/code.mode.javascript.js')
			.script('/js/class/code.mode.css.js')
			.script('/js/class/code.mode.html.js')
			.script('/js/class/code.vim.js')
			.wait(codeInit);

			// TODO: js
			// http://codemirror.net/1/contrib/php/
			/*
			"parsexml.js", 
			"parsecss.js", 
			"tokenizejavascript.js", 
			"parsejavascript.js",
            "../contrib/php/js/tokenizephp.js", 
			"../contrib/php/js/parsephp.js",
            "../contrib/php/js/parsephphtmlmixed.js"
			// TODO: stylesheet
			"../../css/xmlcolors.css", 
			"../../css/jscolors.css", 
			"../../css/csscolors.css", 
			"css/phpcolors.css",
			*/
	}

	$(init);
}(jQuery));