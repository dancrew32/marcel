<? foreach ($domains as $domain): ?>
	<?= r('linode', 'domain', ['domain' => $domain]) ?>
<? endforeach ?>
