<? app::asset('socket_test', 'js') ?>
<? app::asset('getUserMedia', 'js') ?>
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

<div class="row">

	<div class="span6">
		<h3>
		Pager!
		</h3>
		<?= r('common', 'pager', [
			'total' => 50, 
			'page' => 1, 
			'rpp' => 5
		]) ?>
	</div>

	<div class="span6">
		<canvas id="video" width="200" height="150"></canvas>
	</div>

</div>
