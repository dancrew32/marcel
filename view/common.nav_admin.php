<li class="dropdown<? echoif($in_section, ' active') ?>">
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
