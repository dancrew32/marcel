angular.module('app').controllerProvider.register('main.circle', function($scope, abcFactory) {
	// http://vexflow.com/docs/tutorial.html
	var score = document.getElementById('score');	

	var n = abcFactory.note;

	
	var doc  = abcFactory.stave(score, 'treble', {
		width: 300
	});
	var doc2 = abcFactory.stave(score, 'bass', {
		top: 75,
		width: 300
	});


	var v1 = [];
	v1.push([
		n(['c/4'], '4'),
		n(['e/4'], '4')
	]);
	v1.push([
		n(['g/4'], '8'),
		n(['b/4'], '8'),
		n(['d/5'], '8'),
		n(['f/5'], '8')
	]);

	var v2 = [];
	v2.push([
		n(['f/3'], '1', 'b').addAccidental(0, abcFactory.acc('#'))
	]);


	var b = abcFactory.beam(v1[1]);
	//var b2 = abcFactory.beam(v2[1]);

	var v1_out = [];
	var v2_out = [];
	angular.forEach(v1, function(notes) {
		v1_out = v1_out.concat(notes);
	});
	angular.forEach(v2, function(notes) {
		v2_out = v2_out.concat(notes);
	});
	abcFactory.format(doc.ctx, doc.stave, v1_out);
	abcFactory.format(doc2.ctx, doc2.stave, v2_out);
	b.setContext(doc.ctx).draw();
	document.body.className = 'loaded';
	//b2.setContext(doc2.ctx).draw();


	//note(['c/4', 'e/4', 'g/4'], '8').addAccidental(2, acc('#')),

	////var v = voice(n, { num_beats: 4, beat_value: 4, });
	////var format = new Vex.Flow.Formatter().joinVoices([v]).format([v], w);
});
