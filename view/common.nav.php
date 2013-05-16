<div class="navbar navbar-fixed-top<? /*navbar-inverse navbar-fixed-top */ ?>">
	<div class="navbar-inner">
		<div class="container">

			<button type="button" class="btn btn-navbar" 
					data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<a class="brand" href="/">
				<?= APP_NAME ?>
			</a>

			<div class="nav-collapse collapse">

				<ul class="nav">
					<li<? echoif(app::in_section('Home'), ' class="active"') ?>>
						<a href="<?= app::get_path('Home')?>">Home</a>
					</li>


					<? if (
					auth::cron_job_section() ||
					auth::worker_section() ||
					auth::user_section() ||
					auth::user_type_section() ||
					auth::product_type_section() ||
					auth::product_category_section()
					): ?>
						<li class="dropdown<? echoif((app::in_section('Cron') || app::in_section('Worker') || app::in_section('User')), ' active') ?>">
							<a href="#" 
								class="dropdown-toggle" 
								data-toggle="dropdown">
								Admin
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">

								<? if (auth::cron_job_section()): ?>
									<li>
										<a href="<?= app::get_path('Cron Home') ?>">
											Cron
										</a>
									</li>
								<? endif ?>

								<? if (auth::product_category_section()): ?>
									<li>
										<a href="<?= app::get_path('Product Category Home') ?>">
											Product Categories
										</a>
									</li>
								<? endif ?>

								<? if (auth::product_type_section()): ?>
									<li>
										<a href="<?= app::get_path('Product Type Home') ?>">
											Product Types
										</a>
									</li>
								<? endif ?>

								<? if (auth::user_section()): ?>
									<li>
										<a href="<?= app::get_path('User Home') ?>">
											Users
										</a>
									</li>
								<? endif ?>

								<? if (auth::user_type_section()): ?>
									<li>
										<a href="<?= app::get_path('User Type Home') ?>">
											User Types
										</a>
									</li>
								<? endif ?>

								<? if (auth::worker_section()): ?>
									<li>
										<a href="<?= app::get_path('Worker Home') ?>">
											Workers
										</a>
									</li>
								<? endif ?>

							</ul>
						</li>
					<? endif ?>

					<? if (auth::product_section()): ?>
						<li<? echoif(app::in_section('Product'), ' class="active"') ?>>
							<a href="<?= app::get_path('Product Home') ?>">Products</a>
						</li>
					<? endif ?>

					<li<? echoif(app::in_section('Cart'), ' class="active"') ?>>
						<a href="<?= app::get_path('Cart Home') ?>">Cart</a>
					</li>
						
					<li class="dropdown<? echoif(
						app::in_section('Message') || 
						app::in_section('Mustache') ||
						app::in_section('Markdown')
						, ' active') ?>">
						<a href="#" 
							class="dropdown-toggle" 
							data-toggle="dropdown">
							Chat Test
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="<?= app::get_path('Message Home') ?>">Message</a>
							</li>
							<li>
								<a href="<?= app::get_path('Mustache Home') ?>">Mustache</a>
							</li>
							<li>
								<a href="<?= app::get_path('Markdown Home') ?>">Markdown</a>
							</li>
							<? /*
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li class="divider"></li>
							<li class="nav-header">Nav header</li>
							<li><a href="#">Separated link</a></li>
							<li><a href="#">One more separated link</a></li>
							*/ ?>
						</ul>
					</li>

				</ul>

				<ul class="nav pull-right">
					<? if ($logged_in): ?>
						<li class="dropdown">
						<? /*
							
							<div
								class="dropdown-toggle"
								data-toggle="dropdown">
									
								*/ ?>

								<a href="#"
									data-toggle="dropdown">

									<i class="icon-user"></i>
									<? if (User::$user->full_name()): ?>
										<?= User::$user->full_name() ?>
										[<?= take(User::$user->user_type, 'name') ?>]
									<? else: ?>
										Logged In
									<? endif ?>
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="<?= app::get_path('Logout') ?>">Log Out</a>
									</li>
								</ul>
								<? /*
									
							</div>
							*/ ?>
						</li>
					<? else: ?>
						<li>
							<a href="<?= app::get_path('Login') ?>">
								Login
							</a>
						</li>
						<li>
							<a href="<?= app::get_path('Join') ?>">
								Join	
							</a>
						</li>
					<? endif ?>
				</ul>

				<? /*
				<form class="navbar-form pull-right">
					<input class="span2" type="text" placeholder="Email">
					<input class="span2" type="password" placeholder="Password">
					<button type="submit" class="btn">Sign in</button>
				</form>
				*/ ?>

			</div>

		</div>
	</div>
</div>

<? if (note::get('login:success')): ?>
	<div class="container">
		<?= html::alert("You're now logged in.", ['type'=>'success']) ?>
	</div>
<? endif ?>

<? if (note::get('logout:success')): ?>
	<div class="container">
		<?= html::alert("You're now logged out.", ['type'=>'success']) ?>
	</div>
<? endif ?>
