<? foreach ($links as $k => $v): ?>
	<li<? echoif($filter == $k, ' class="active"') ?>>
		<?= html::a($v) ?>
	</li>
<? endforeach ?>
