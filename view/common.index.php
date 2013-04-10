<div class="hero-unit">
	<h1>Marcel</h1>
	<p>
	The MVC with shoes on!
	</p>
	<p><a href="#" class="btn btn-primary btn-large">Learn more &raquo;</a></p>
</div>

<div class="row">
	<div class="span4">
		<h2>This Page</h2>
<pre>
controller/common.php#index
view/common.index.php	
</pre>
		<p><a class="btn" href="#">View details &raquo;</a></p>
	</div>
	<div class="span4">
		<h2>Image Cache</h2>
		<?= image::get([
			'src' => '/img/drwho.jpg',
			'w'   => 300,
			'h'   => 200,
		], true) ?>
		<p style="margin-top: 10px;">
			<?= html::btn('#', 'See more &raquo;') ?>
		</p>
	</div>
	<div class="span4">
		<h2>Login</h2>
		<?= r('authentication', 'login') ?>
	</div>
</div>


<div class="row">
	<div class="span4">
		<?= r('form_test', 'index') ?>
	</div>
	<div class="span4">
		<?= r('form_test', 'modal') ?>
	</div>
	<div class="span4">
		<? /*
		<?= r('form_test', 'popover') ?>
		*/ ?>
		<?= r('common', 'media_rows') ?>
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
