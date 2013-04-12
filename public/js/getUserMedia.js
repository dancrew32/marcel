window.URL = window.URL || window.webkitURL;
navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
window.requestAnimationFrame = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.msRequestAnimationFrame || window.oRequestAnimationFrame;

function cam(context, draw) {
	var self = this;
	this.context = context;
	this.draw = draw;

	var streamContainer = $('<div>');
	this.video = $('<video>');
	this.video.attr('autoplay', '1');
	this.video.attr('width', this.context.canvas.width);
	this.video.attr('height', this.context.canvas.height);
	this.video.attr('style', 'display:none');
	streamContainer.append(this.video);
	$(document.body).append(streamContainer)

	navigator.getUserMedia({
		video: true,
		audio: true
	}, function(stream) {
		self.video[0].src = window.URL.createObjectURL(stream);
		self.update();
		// TODO: emit stream to websocket blob for broadcast
	});

	this.update = function() {
		var self = this;
		var last = Date.now();
		var loop = function() {
			var dt = Date.now - last;
			self.draw(self.video[0], dt);
			last = Date.now();
			requestAnimationFrame(loop);
		}
		requestAnimationFrame(loop);
	}; 
}

$(function() {
	var video = $('#video');
	var context = video[0].getContext('2d');
	function draw(img, dt) {
		context.drawImage(img, 0, 0, 200, 150);
	}
	new cam(context, draw);
});
