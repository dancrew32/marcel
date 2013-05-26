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

					<? if (auth::can(array_keys($admin_nav))): ?>
						<li class="dropdown<? echoif(app::in_sections($admin_nav_sections), ' active') ?>">
							<a href="#" 
								class="dropdown-toggle" 
								data-toggle="dropdown">
								Admin
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">

								<? foreach ($admin_nav as $auth => $nav): ?>
									<? if (!auth::can([$auth])) { continue; } ?>

									<li>
										<a href="<?= app::get_path($nav['path']) ?>">
											<?= $nav['text'] ?>
										</a>
									</li>

								<? endforeach ?>

							</ul>
						</li>
					<? endif ?>

					<? if (auth::can(['product'])): ?>
						<li<? echoif(app::in_section('Product'), ' class="active"') ?>>
							<a href="<?= app::get_path('Product Home') ?>">Products</a>
						</li>
					<? endif ?>

					<? if (auth::can(['cart'])): ?>
						<li<? echoif(app::in_section('Cart'), ' class="active"') ?>>
							<a href="<?= app::get_path('Cart Home') ?>"><?= Cart::NAME ?></a>
						</li>
					<? endif ?>
						
					<li class="dropdown<? echoif(app::in_sections(['Message', 'Mustache', 'Markdown']), ' active') ?>">
						<a href="#" 
							class="dropdown-toggle" 
							data-toggle="dropdown">
							Tests
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
							<? if (auth::can(['stock'])): ?>
								<li>
									<a href="<?= app::get_path('Stock Home') ?>">Stocks</a>
								</li>
							<? endif ?>
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
									<? if (strlen(User::$user->full_name())): ?>
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

<? if (note::get('user_verification:success')): ?>
	<div class="container">
		<?= html::alert("Thanks for verifiying your email address!", ['type'=>'success']) ?>
	</div>
<? endif ?>

<? if (note::get('user_verification:sent')): ?>
	<div class="container">
		<?= html::alert(
			"We just sent you a verfication email. ".
			html::btn('https://'.User::$user->email_domain(), 'Go to '. User::$user->email_domain(), 'globe'),
			['type'=>'success']
		) ?>
	</div>
<? endif ?>

<? if (note::get('user_verification:failure')): ?>
	<div class="container">
		<?= html::alert(
			"We were unable to verify your email address. ". 
			html::btn(
				app::get_path('User Verification Resend'), 
				'Resend Verification Email', 
				'envelope'
			), ['type'=>'error']) 
		?>
	</div>
<? endif ?>
