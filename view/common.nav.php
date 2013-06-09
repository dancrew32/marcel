<div class="navbar navbar-fixed-top<? /*navbar-inverse navbar-fixed-top */ ?>">
	<div class="navbar-inner">
		<div class="container">

			<?= r('common', 'nav_collapse') ?>

			<a class="brand" href="/">
				<?= APP_NAME ?>
			</a>

			<div class="nav-collapse collapse">
				<ul class="nav">

					<li<? echoif(route::in_section('Home'), ' class="active"') ?>>
						<a href="<?= route::get('Home')?>">Home</a>
					</li>

					<?= r('common', 'nav_admin') ?>

					<? if (auth::can(['product'])): ?>
						<li<? echoif(route::in_section('Product'), ' class="active"') ?>>
							<a href="<?= route::get('Product Home') ?>">Products</a>
						</li>
					<? endif ?>

					<? if (auth::can(['cart'])): ?>
						<li<? echoif(route::in_section('Cart'), ' class="active"') ?>>
							<a href="<?= route::get('Cart Home') ?>"><?= Cart::NAME ?></a>
						</li>
					<? endif ?>
						
					<?= r('common', 'nav_test') ?>

				</ul>

				<ul class="nav pull-right">
					<?= r('common', 'nav_user') ?>
				</ul>

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
				route::get('User Verification Resend'), 
				'Resend Verification Email', 
				'envelope'
			), ['type'=>'error']) 
		?>
	</div>
<? endif ?>
