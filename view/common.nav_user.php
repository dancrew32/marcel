<? if ($logged_in): ?>
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
				<a href="<?= app::get_path('Logout') ?>">Log Out</a>
			</li>
		</ul>
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
