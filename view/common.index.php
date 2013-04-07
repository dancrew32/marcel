<div id="main">
	<div class="inner">
		<h1>
			Marcel
		</h1>

		<p>
			Is working! See:
		</p>

<pre>
controller/common.php#index
view/common.index.php	
</pre>

		<h2>
			Image Cache
		</h2>
		<?= image::get([
			'src' => '/img/drwho.jpg',
			'w'   => 250,
			'h'   => 250,
		], true) ?>
	</div>
</div>

<script>
(function() {
var ws = new WebSocket("ws://0.0.0.0:7334");
window.ws = ws;
ws.onclose = function() {
	console.log('closed');
};
ws.onmessage = function(msg) {
	console.log(msg.data);	
};
ws.error = function(e) {
	console.log('error');
	console.log(e);	
};
ws.onopen = function() {
	console.log('socket opened');		
};
}());
</script>
