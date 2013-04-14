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
		<?= r('authentication', 'login', ['simple_mode' => true]) ?>
	</div>
</div>


<div class="row">
	<div class="span4">
		<h2>
			Form Fields	
		</h2>
		<?= r('form_test', 'index') ?>
	</div>
	<div class="span4">
		<h2>
			Modals	
		</h2>
		<?= r('form_test', 'modal') ?>
	</div>
	<div class="span4">
		<h2>
			Media Rows	
		</h2>
		<?= r('common', 'media_rows') ?>
	</div>
</div>

<div class="row">

	<div class="span6">
		<h2>
		Pager
		</h2>
		<?= r('common', 'pager', [
			'total' => 50, 
			'page' => 1, 
			'rpp' => 5
		]) ?>
	</div>
	<div class="span6">
		<h2>Alerts</h2>
	 	<div class="alert">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Warning!</strong> 
			Single Liner
		</div>
	 	<div class="alert alert-block">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<h4 class="alert-heading">
				Big warning!
			</h4>
			Multiple line warning messages should use the <code>.alert-block</code> class
			to allow for extra rendering room.
		</div>	
	 	<div class="alert alert-error alert-block">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Error!</strong> 
			Oh snap!
			<br>
			<?= new field('button', [
				'data-dismiss' => 'alert',
				'class' => 'btn btn-danger',
				'text' => 'Action!',
		    ]) ?>
			<a href="#" data-dismiss="alert" class="btn">Alt Action</a>
		</div>	
	 	<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Yay!</strong> 
			Successfully updated that thing.
		</div>	
	 	<div class="alert alert-info">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Just so you know</strong>
			this is a regular info box.
		</div>	
	</div>
</div>

<div class="row">
	<div class="span4 offset4">
		<h2>Carousel</h2>
		<div class="inner">
			<?= r('common', 'carousel') ?>
		</div>
	</div>
</div>



</div>
