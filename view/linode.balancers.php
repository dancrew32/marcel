<? foreach ($balancers as $balancer): ?>
	<?= r('linode', 'balancer', ['balancer' => $balancer]) ?>
<? endforeach ?>
