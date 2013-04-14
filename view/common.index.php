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
	</div>
	<div class="span4">
		<h2>Image Cache</h2>
		<?= image::get([
			'src' => '/img/drwho.jpg',
			'w'   => 300,
			'h'   => 200,
		], true) ?>
	</div>
	<div class="span4">
		<h2>Login</h2>
		<?= r('authentication', 'login', ['simple_mode' => true]) ?>
	</div>
</div>


<div class="row">


	<div class="span4">
		<h2>
			Modals	
		</h2>
		<?= r('form_test', 'modal') ?>
		<h2>Labels<h2>
		<span class="label">Default</span>
		<span class="label label-success">Success</span>
		<span class="label label-warning">Warning</span>
		<span class="label label-important">Important</span>
		<span class="label label-info">Info</span>
		<span class="label label-info">Info</span>

		<h2>Badges</h2>
		<span class="badge">1</span>
		<span class="badge badge-success">2</span>
		<span class="badge badge-warning">4</span>
		<span class="badge badge-important">6</span>
		<span class="badge badge-info">8</span>
		<span class="badge badge-inverse">10</span>

	</div>

	<div class="span4">
		<h2>
			Media Rows	
		</h2>
		<?= r('common', 'media_rows') ?>
	</div>

	<div class="span4">
		<h2>Thumbnails</h2>
		<ul class="thumbnails">
			<? for ($i = 0; $i < 4; $i++): ?>
				<li class="span1">
					<a href="#" class="thumbnail">		
						<?= image::get([
							'src' => '/img/drwho.jpg',
							'w'   => 50,
							'h'   => 50,
						], true) ?>
					</a>
				</li>
			<? endfor ?>
		</ul>
		<ul class="thumbnails">
			<? for ($i = 0; $i < 2; $i++): ?>
				<li class="span2">
					<div class="thumbnail">		
						<?= image::get([
							'src' => '/img/drwho.jpg',
							'w'   => 150,
							'h'   => 120,
						], true) ?>
						<h4>Thumb <?= $i+1 ?></h4>
						<p>Content for thumb <?= $i+1 ?></p>
					</div>
				</li>
			<? endfor ?>
		</ul>
	</div>

</div>

<div class="row">
	<div class="span6">
		<h2>Tabs</h2>
		<ul class="nav nav-tabs">
			<li class="active"><a href="#home" data-toggle="tab">Home</a></li>
			<li><a href="#profile" data-toggle="tab">Profile</a></li>
			<li><a href="#messages" data-toggle="tab">Messages</a></li>
			<li><a href="#settings" data-toggle="tab">Settings</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="home">
				My Home content
			</div>
			<div class="tab-pane" id="profile">
				My Profile content
			</div>
			<div class="tab-pane" id="messages">
				My messages content
			</div>
			<div class="tab-pane" id="settings">
				My settings content
			</div>
		</div>

		<h2>Tabs Stacked</h2>
		<ul class="nav nav-tabs nav-stacked">
			<li class="active"><a href="#home" data-toggle="tab">Home</a></li>
			<li><a href="#profile" data-toggle="tab">Profile</a></li>
			<li><a href="#messages" data-toggle="tab">Messages</a></li>
			<li><a href="#settings" data-toggle="tab">Settings</a></li>
		</ul>

	</div>

	<div class="span6">
		<h2>Pills</h2>
		<ul class="nav nav-pills">
			<li class="active"><a href="#">Take</a></li>
			<li><a href="#">Your</a></li>
			<li><a href="#">Pills</a></li>
		</ul>

		<h2>Pills Stacked</h2>
		<ul class="nav nav-pills nav-stacked">
			<li class="active"><a href="#">Take</a></li>
			<li><a href="#">Your</a></li>
			<li><a href="#">Pills</a></li>
		</ul>
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

		<h2>
			Progress
		</h2>
		<div class="progress">
			<div class="bar" style="width: 60%;"></div>
		</div>
		<div class="progress progress-striped active">
			<div class="bar" style="width: 40%;"></div>
		</div>
		<div class="progress">
			<div class="bar bar-info" style="width: 7%;"></div>
			<div class="bar bar-success" style="width: 35%;"></div>
			<div class="bar bar-warning" style="width: 20%;"></div>
			<div class="bar bar-danger" style="width: 10%;"></div>
		</div>
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
	<div class="span4">
		<h2>
			Form Fields	
		</h2>
		<?= r('form_test', 'index') ?>
	</div>

	<div class="span4 offset4">
		<h2>Carousel</h2>
		<p>
			This row is also designed to show off offset. (see <code>.offset4</code>)
		</p>
		<div class="inner">
			<?= r('common', 'carousel') ?>
		</div>
	</div>
</div>

