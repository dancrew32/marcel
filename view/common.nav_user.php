<? if ($user && $logged_in): ?>
	<li class="dropdown">
		<a href="#"
			data-toggle="dropdown">

			<i class="icon-user"></i>
			<? if (strlen($user->full_name())): ?>
				<?= $user->full_name() ?>
				[<?= take($user->user_type, 'name') ?>]
			<? else: ?>
				Logged In
			<? endif ?>
			<b class="caret"></b>
		</a>
		<ul class="dropdown-menu">
			<li>
				<a href="<?= route::get('Logout') ?>">Log Out</a>
			</li>
		</ul>
	</li>
<? else: ?>
	<li>
		<a href="<?= route::get('Login') ?>">
			Login
		</a>
	</li>
	<li>
		<a href="<?= route::get('Join') ?>">
			Join	
		</a>
	</li>
<? endif ?>
