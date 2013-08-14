angular.module('app').provide.factory('abcFactory', function() {
	return {
		stave: function(el, type, options) {
			options = angular.extend({
				left: 0,	
				top: 0,
				width: 300 // mobile phone width
			}, options || {});
			var vex   = new Vex.Flow.Renderer(el, Vex.Flow.Renderer.Backends.CANVAS); 
			var ctx   = vex.getContext();
			var stave = new Vex.Flow.Stave(options.left, options.top, options.width);
			stave.addClef(type).setContext(ctx).draw();
			return { 
				stave: stave,
				ctx: ctx
			};
		},

		note: function(keys, duration, clef) {
			switch (clef) {
				case 'b': clef = 'bass'; break;
				case 'a': clef = 'alto'; break;
				case 't': clef = 'tenor'; break;
				default:
					clef = 'treble';
			}
			return new Vex.Flow.StaveNote({keys: keys, duration: duration, clef: clef});
		},

		acc: function(type) {
			return new Vex.Flow.Accidental(type);
		},

		voice: function(notes, data) {
			data = angular.extend(data || {}, {
				resolution: Vex.Flow.RESOLUTION
			});
			var voice = new Vex.Flow.Voice(data);
			voice.addTickables(notes);
			return voice;
		},

		beam: function(notes) {
			return new Vex.Flow.Beam(notes);
		},

		format: function(ctx, stave, notes) {
			return Vex.Flow.Formatter.FormatAndDraw(ctx, stave, notes);
		},

		clear: function(el) {
			return el.getContext('2d').clearRect(0, 0, score.width, score.height);
		}

	};
});
