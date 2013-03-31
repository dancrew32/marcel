<div id="main">
	<div class="inner">
		<form action="<?= $action ?>" method="post">
		<? foreach ($fields as $k => $v): ?>
			<?= r('form', 'input', [
				'name'  => $k,
				'placeholder' => take($v, 'placeholder'),
				'value' => take($v, 'value'),
				'type'  => take($v, 'type'),
			]) ?>
		<? endforeach ?>
		<?= r('form', 'submit', ['text' => 'Login']) ?>
		</form>
	</div>
</div>
