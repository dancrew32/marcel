<div class="navbar<? /*navbar-inverse navbar-fixed-top */ ?>">
	<div class="navbar-inner">
		<div class="container">

			<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
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
					<li<? echoif(app::in_section('Cron'), ' class="active"') ?>>
						<a href="<?= app::get_path('Cron Home') ?>">Cron</a>
					</li>
					<li<? echoif(app::in_section('Worker'), ' class="active"') ?>>
						<a href="<?= app::get_path('Worker Home') ?>">Workers</a>
					</li>
					<li<? echoif(app::in_section('User'), ' class="active"') ?>>
						<a href="<?= app::get_path('User Home') ?>">Users</a>
					</li>
					<li<? echoif(app::in_section('Cart'), ' class="active"') ?>>
						<a href="<?= app::get_path('Cart Home') ?>">Cart</a>
					</li>
					<? /*
						
					<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="#">Action</a></li>
						<li><a href="#">Another action</a></li>
						<li><a href="#">Something else here</a></li>
						<li class="divider"></li>
						<li class="nav-header">Nav header</li>
						<li><a href="#">Separated link</a></li>
						<li><a href="#">One more separated link</a></li>
					</ul>
					</li>
					*/ ?>
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
										[<?= User::$user->role ?>]
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
