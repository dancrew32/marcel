<ul class="nav nav-pills pull-right nav-output-style">
	<li<?echoif($output_style == 'media', ' class="active"') ?>>
		<a href="<?= $root_path ?>/<?= $page ?>">Media</a>
	</li>
	<li<?echoif($output_style == 'table', ' class="active"') ?>>
		<a href="<?= $root_path ?>/<?= $page ?>.table">Table</a>
	</li>
	<li<?echoif($output_style == 'json', ' class="active"') ?>>
		<a href="<?= $root_path ?>/<?= $page ?>.json">JSON</a>
	</li>
</ul>
