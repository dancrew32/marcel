<div class="hero-unit">
	<h1>Marcel</h1>
	<p>
	The MVC with shoes on!
	</p>
	<p><a href="https://github.com/dancrew32/marcel" 
		class="btn btn-primary btn-large">Learn more &raquo;</a></p>
	<? if (detect::is_mobile()): ?>
		Looks like you're using a mobile device!
	<? endif ?>
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
	<div class="span4 scrolling">
		<? if (!User::$logged_in): ?>
			<h2>Login</h2>
			<?= r('authentication', 'login', ['simple_mode' => true]) ?>
		<? else: ?>
			<h2>User Info</h2>
			<? pp(User::$user) ?>
		<? endif ?>
	</div>
</div>


<div class="row">


	<div class="span4">
		<h2>
			Modals	
		</h2>
		<?= r('form_test', 'modal') ?>
		<h2>Labels</h2>
		<span class="label">Default</span>
		<span class="label label-success">Success</span>
		<span class="label label-warning">Warning</span>
		<span class="label label-important">Important</span>
		<span class="label label-info">Info</span>
		<span class="label label-inverse">Info</span>

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

		<div class="media">
			<a href="#" class="pull-left">
				<img src="<?= image::get([
					'src' => '/img/drwho.jpg',
					'w' => 65,
					'h' => 65,
				]) ?>" alt="" class="media-object" />
			</a>
			<div class="media-body">
				<h4 class="media-heading">Media rows</h4>
				<p>
					Here is some fun content.	
				</p>
			</div>
		</div>
		<div class="media">
			<a href="#" class="pull-left">
				<img src="<?= image::get([
					'src' => '/img/drwho.jpg',
					'w' => 65,
					'h' => 65,
				]) ?>" alt="" class="media-object" />
			</a>
			<div class="media-body">
				<h4 class="media-heading">Media rows</h4>
				<p>
					Here is some fun content.	
				</p>
			</div>
		</div>
		<div class="media">
			<a href="#" class="pull-left">
				<img src="<?= image::get([
					'src' => '/img/drwho.jpg',
					'w' => 65,
					'h' => 65,
				]) ?>" alt="" class="media-object" />
			</a>
			<div class="media-body">
				<h4 class="media-heading">Media rows</h4>
				<p>
					Here is some fun content.	
				</p>
			</div>
		</div>
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
	<div class="span8">
		<h2>
			Tables
		</h2>
		<table class="table table-bordered table-hover <? /* table-striped table-condensed */ ?> ">
			<thead>
				<tr>
					<th>#</th>
					<th>Product</th>
					<th>Payment Taken</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
			<tr class="success">
				<td>1</td>
				<td>TB - Monthly</td>
				<td>01/04/2012</td>
				<td>Approved</td>
			</tr>
			<tr class="error">
				<td>2</td>
				<td>TB - Monthly</td>
				<td>02/04/2012</td>
				<td>Declined</td>
			</tr>
			<tr class="warning">
				<td>3</td>
				<td>TB - Monthly</td>
				<td>03/04/2012</td>
				<td>Pending</td>
			</tr>
			<tr class="info">
				<td>4</td>
				<td>TB - Monthly</td>
				<td>04/04/2012</td>
				<td>Call in to confirm</td>
			</tr>
			<tr>
				<td>5</td>
				<td>TB - Monthly</td>
				<td>04/04/2012</td>
				<td>
					<div>	
						Call in to confirm
						<br>
						Tons of data in this one cell
					</div>
				</td>
			</tr>
			</tbody>
		</table>

		<h3>Using html::table</h3>
		<?= html::table([
			[
				'id' => 1,
				'name' => 'Dan',
				'site' => 'danmasq.com',
			],
			[
				'id' => 2,
				'name' => 'Marcel',
				'site' => 'google.com',
			],
			[
				'id'     => 3,
				'_class' => 'warning',
				'name'   => 'Foo',
				'site'   => 'bar.com',
			],
			[
				'id'     => 4,
				'_class' => 'info',
				'name'   => 'Foo',
				'site'   => 'bar.com',
			],
			[
				'id'     => 5,
				'_class' => 'error',
				'name'   => 'Foo',
				'site'   => 'bar.com',
			],
			[
				'id'     => 6,
				'_class' => 'success',
				'name'   => 'Foo',
				'site'   => 'bar.com',
			],
		], [
			'delete_col'     => true,
			'delete_url'     => '#',
			'primary_key'    => 'id',
			'hidden_columns' => ['id'],
			'table_class'    => 'table table-hover table-condensed table-striped table-bordered',
		]) ?>

		<div class="row">
			<div class="span4 offset4">
				<h2>Carousel</h2>
				<p>
					This row is also designed to show off offset. (see <code>.offset4</code>)
				</p>
				<div class="inner">
					<div id="myCarousel" class="carousel slide">
						<? /*
						<ol class="carousel-indicators">
							<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
							<li data-target="#myCarousel" data-slide-to="1"></li>
							<li data-target="#myCarousel" data-slide-to="2"></li>
						</ol>
						*/ ?>
						<div class="carousel-inner">
							<div class="active item">Slide A</div>
							<div class="item">Slide B</div>
							<div class="item">Slide C</div>
						</div>
						<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
						<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
					</div>	
				</div>
			</div>
		</div>

		<div class="row">

			<div class="span8">
				<h2>Icons</h2>
				<style>
.the-icons li { float: left; width: 25%; list-style: none;  }	
				</style>
				<ul class="the-icons clearfix">
					<li><i class="icon-glass"></i> icon-glass</li>
					<li><i class="icon-music"></i> icon-music</li>
					<li><i class="icon-search"></i> icon-search</li>
					<li><i class="icon-envelope"></i> icon-envelope</li>
					<li><i class="icon-heart"></i> icon-heart</li>
					<li><i class="icon-star"></i> icon-star</li>
					<li><i class="icon-star-empty"></i> icon-star-empty</li>
					<li><i class="icon-user"></i> icon-user</li>
					<li><i class="icon-film"></i> icon-film</li>
					<li><i class="icon-th-large"></i> icon-th-large</li>
					<li><i class="icon-th"></i> icon-th</li>
					<li><i class="icon-th-list"></i> icon-th-list</li>
					<li><i class="icon-ok"></i> icon-ok</li>
					<li><i class="icon-remove"></i> icon-remove</li>
					<li><i class="icon-zoom-in"></i> icon-zoom-in</li>
					<li><i class="icon-zoom-out"></i> icon-zoom-out</li>
					<li><i class="icon-off"></i> icon-off</li>
					<li><i class="icon-signal"></i> icon-signal</li>
					<li><i class="icon-cog"></i> icon-cog</li>
					<li><i class="icon-trash"></i> icon-trash</li>
					<li><i class="icon-home"></i> icon-home</li>
					<li><i class="icon-file"></i> icon-file</li>
					<li><i class="icon-time"></i> icon-time</li>
					<li><i class="icon-road"></i> icon-road</li>
					<li><i class="icon-download-alt"></i> icon-download-alt</li>
					<li><i class="icon-download"></i> icon-download</li>
					<li><i class="icon-upload"></i> icon-upload</li>
					<li><i class="icon-inbox"></i> icon-inbox</li>

					<li><i class="icon-play-circle"></i> icon-play-circle</li>
					<li><i class="icon-repeat"></i> icon-repeat</li>
					<li><i class="icon-refresh"></i> icon-refresh</li>
					<li><i class="icon-list-alt"></i> icon-list-alt</li>
					<li><i class="icon-lock"></i> icon-lock</li>
					<li><i class="icon-flag"></i> icon-flag</li>
					<li><i class="icon-headphones"></i> icon-headphones</li>
					<li><i class="icon-volume-off"></i> icon-volume-off</li>
					<li><i class="icon-volume-down"></i> icon-volume-down</li>
					<li><i class="icon-volume-up"></i> icon-volume-up</li>
					<li><i class="icon-qrcode"></i> icon-qrcode</li>
					<li><i class="icon-barcode"></i> icon-barcode</li>
					<li><i class="icon-tag"></i> icon-tag</li>
					<li><i class="icon-tags"></i> icon-tags</li>
					<li><i class="icon-book"></i> icon-book</li>
					<li><i class="icon-bookmark"></i> icon-bookmark</li>
					<li><i class="icon-print"></i> icon-print</li>
					<li><i class="icon-camera"></i> icon-camera</li>
					<li><i class="icon-font"></i> icon-font</li>
					<li><i class="icon-bold"></i> icon-bold</li>
					<li><i class="icon-italic"></i> icon-italic</li>
					<li><i class="icon-text-height"></i> icon-text-height</li>
					<li><i class="icon-text-width"></i> icon-text-width</li>
					<li><i class="icon-align-left"></i> icon-align-left</li>
					<li><i class="icon-align-center"></i> icon-align-center</li>
					<li><i class="icon-align-right"></i> icon-align-right</li>
					<li><i class="icon-align-justify"></i> icon-align-justify</li>
					<li><i class="icon-list"></i> icon-list</li>

					<li><i class="icon-indent-left"></i> icon-indent-left</li>
					<li><i class="icon-indent-right"></i> icon-indent-right</li>
					<li><i class="icon-facetime-video"></i> icon-facetime-video</li>
					<li><i class="icon-picture"></i> icon-picture</li>
					<li><i class="icon-pencil"></i> icon-pencil</li>
					<li><i class="icon-map-marker"></i> icon-map-marker</li>
					<li><i class="icon-adjust"></i> icon-adjust</li>
					<li><i class="icon-tint"></i> icon-tint</li>
					<li><i class="icon-edit"></i> icon-edit</li>
					<li><i class="icon-share"></i> icon-share</li>
					<li><i class="icon-check"></i> icon-check</li>
					<li><i class="icon-move"></i> icon-move</li>
					<li><i class="icon-step-backward"></i> icon-step-backward</li>
					<li><i class="icon-fast-backward"></i> icon-fast-backward</li>
					<li><i class="icon-backward"></i> icon-backward</li>
					<li><i class="icon-play"></i> icon-play</li>
					<li><i class="icon-pause"></i> icon-pause</li>
					<li><i class="icon-stop"></i> icon-stop</li>
					<li><i class="icon-forward"></i> icon-forward</li>
					<li><i class="icon-fast-forward"></i> icon-fast-forward</li>
					<li><i class="icon-step-forward"></i> icon-step-forward</li>
					<li><i class="icon-eject"></i> icon-eject</li>
					<li><i class="icon-chevron-left"></i> icon-chevron-left</li>
					<li><i class="icon-chevron-right"></i> icon-chevron-right</li>
					<li><i class="icon-plus-sign"></i> icon-plus-sign</li>
					<li><i class="icon-minus-sign"></i> icon-minus-sign</li>
					<li><i class="icon-remove-sign"></i> icon-remove-sign</li>
					<li><i class="icon-ok-sign"></i> icon-ok-sign</li>

					<li><i class="icon-question-sign"></i> icon-question-sign</li>
					<li><i class="icon-info-sign"></i> icon-info-sign</li>
					<li><i class="icon-screenshot"></i> icon-screenshot</li>
					<li><i class="icon-remove-circle"></i> icon-remove-circle</li>
					<li><i class="icon-ok-circle"></i> icon-ok-circle</li>
					<li><i class="icon-ban-circle"></i> icon-ban-circle</li>
					<li><i class="icon-arrow-left"></i> icon-arrow-left</li>
					<li><i class="icon-arrow-right"></i> icon-arrow-right</li>
					<li><i class="icon-arrow-up"></i> icon-arrow-up</li>
					<li><i class="icon-arrow-down"></i> icon-arrow-down</li>
					<li><i class="icon-share-alt"></i> icon-share-alt</li>
					<li><i class="icon-resize-full"></i> icon-resize-full</li>
					<li><i class="icon-resize-small"></i> icon-resize-small</li>
					<li><i class="icon-plus"></i> icon-plus</li>
					<li><i class="icon-minus"></i> icon-minus</li>
					<li><i class="icon-asterisk"></i> icon-asterisk</li>
					<li><i class="icon-exclamation-sign"></i> icon-exclamation-sign</li>
					<li><i class="icon-gift"></i> icon-gift</li>
					<li><i class="icon-leaf"></i> icon-leaf</li>
					<li><i class="icon-fire"></i> icon-fire</li>
					<li><i class="icon-eye-open"></i> icon-eye-open</li>
					<li><i class="icon-eye-close"></i> icon-eye-close</li>
					<li><i class="icon-warning-sign"></i> icon-warning-sign</li>
					<li><i class="icon-plane"></i> icon-plane</li>
					<li><i class="icon-calendar"></i> icon-calendar</li>
					<li><i class="icon-random"></i> icon-random</li>
					<li><i class="icon-comment"></i> icon-comment</li>
					<li><i class="icon-magnet"></i> icon-magnet</li>

					<li><i class="icon-chevron-up"></i> icon-chevron-up</li>
					<li><i class="icon-chevron-down"></i> icon-chevron-down</li>
					<li><i class="icon-retweet"></i> icon-retweet</li>
					<li><i class="icon-shopping-cart"></i> icon-shopping-cart</li>
					<li><i class="icon-folder-close"></i> icon-folder-close</li>
					<li><i class="icon-folder-open"></i> icon-folder-open</li>
					<li><i class="icon-resize-vertical"></i> icon-resize-vertical</li>
					<li><i class="icon-resize-horizontal"></i> icon-resize-horizontal</li>
					<li><i class="icon-hdd"></i> icon-hdd</li>
					<li><i class="icon-bullhorn"></i> icon-bullhorn</li>
					<li><i class="icon-bell"></i> icon-bell</li>
					<li><i class="icon-certificate"></i> icon-certificate</li>
					<li><i class="icon-thumbs-up"></i> icon-thumbs-up</li>
					<li><i class="icon-thumbs-down"></i> icon-thumbs-down</li>
					<li><i class="icon-hand-right"></i> icon-hand-right</li>
					<li><i class="icon-hand-left"></i> icon-hand-left</li>
					<li><i class="icon-hand-up"></i> icon-hand-up</li>
					<li><i class="icon-hand-down"></i> icon-hand-down</li>
					<li><i class="icon-circle-arrow-right"></i> icon-circle-arrow-right</li>
					<li><i class="icon-circle-arrow-left"></i> icon-circle-arrow-left</li>
					<li><i class="icon-circle-arrow-up"></i> icon-circle-arrow-up</li>
					<li><i class="icon-circle-arrow-down"></i> icon-circle-arrow-down</li>
					<li><i class="icon-globe"></i> icon-globe</li>
					<li><i class="icon-wrench"></i> icon-wrench</li>
					<li><i class="icon-tasks"></i> icon-tasks</li>
					<li><i class="icon-filter"></i> icon-filter</li>
					<li><i class="icon-briefcase"></i> icon-briefcase</li>
					<li><i class="icon-fullscreen"></i> icon-fullscreen</li>
				</ul>
			</div>

		</div>


	</div>


	<div class="span4">
		<h2>Circles</h2>
		<ul class="thumbnails">
			<li class="span2">
			<div>
			<img src="<?= image::get([
			'src' => '/img/drwho.jpg',
			'w'   => 200,
			'h'   => 200,
			]) ?>" class="img-circle"/>
			</div>
			</li>
			<? for ($i = 0; $i < 4; $i++): ?>
			<li class="span1">
			<div>
			<img src="<?= image::get([
			'src' => '/img/drwho.jpg',
			'w'   => 200,
			'h'   => 200,
			]) ?>" class="img-circle"/>
			</div>
			</li>
			<? endfor ?>
		</ul>
	</div>
	<div class="span4">
		<h2>Tooltips</h2>
		<p>
		This has to be initialized in JavaScript (no magic data-attribute binding)
		<code>$('#my-tip').tooltip({ selector: 'a' });</code>
		</p>
		<ul id="my-tip">
			<li><a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tooltip on top">Tooltip on top</a></li>
			<li><a href="#" data-toggle="tooltip" data-placement="right" title="" data-original-title="Tooltip on right">Tooltip on right</a></li>
			<li><a href="#" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Tooltip on bottom">Tooltip on bottom</a></li>
			<li><a href="#" data-toggle="tooltip" data-placement="left" title="Tooltip on left">Tooltip on left</a></li>
		</ul>
	</div>

</div>

<div class="row">
	<div class="span4">
		<h2>Accordion</h2>
		<div class="accordion" id="accordion2">
			<? for ($i = 0; $i < 3; $i++): ?>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" 
							data-toggle="collapse" 
							data-parent="#accordion2" 
							href="#collapse<?= $i?>">
							Group Item #<?= $i+1 ?>
						</a>
					</div>
					<div id="collapse<?= $i ?>" class="accordion-body collapse<? echoif(!$i, ' in') ?>">
						<div class="accordion-inner">
							Your accordion content
						</div>
					</div>
				</div>
			<? endfor ?>
		</div>
	</div>

	<div class="span4">	
		<h2>Popovers</h2>
		<p>
		This has to be initialized in JavaScript (no magic data-attribute binding)
		<code>$('#popit button').popover();</code>
		This doesn't delegate like tooltip, so use it sparingly
		</p>
		<ul id="popit">
			<li><button class="btn" data-toggle="popover" data-placement="top" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." title="" data-original-title="Popover on top">Popover on top</button></li>
			<li><button class="btn" data-toggle="popover" data-placement="right" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." title="" data-original-title="Popover on right">Popover on right</button></li>
			<li><button class="btn" data-toggle="popover" data-placement="bottom" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." title="" data-original-title="Popover on bottom">Popover on bottom</button></li>
			<li><button class="btn" data-toggle="popover" data-placement="left" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." title="" data-original-title="Popover on left">Popover on left</button></li>
		</ul>
	</div>

</div>
