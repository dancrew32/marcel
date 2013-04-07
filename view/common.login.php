<?= form::open($action, 'post', ['class' => 'form-inline']) ?>
	<?= r('form', 'input', [
		'name' => 'user',
		'id' => 'user',
		'placeholder' => 'Username',
		'value' => h($u),
	]) ?>
	<?= r('form', 'input', [
		'name' => 'pass',
		'id' => 'pass',
		'placeholder' => 'Password',
	]) ?>
	<?= r('form', 'button', ['text' => 'Login']) ?>
</form>
