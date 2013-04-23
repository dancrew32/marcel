<div class="media-body">
	<h4 class="media-heading">
		<? if (isset($user->active)): ?>
			<?= $user->badge('pull-right') ?>
		<? endif ?>

		<?= h($user->full_name()) ?>

		<? if (isset($user->email{0})): ?>
			<small>
				<?= h($user->email) ?>
			</small>
		<? endif ?>
	</h4>
	<ul class="nav nav-pills last">
		<li>
			<?= html::a([
				'href' => "{$root_path}/edit/{$user->id}", 
				'text' => "Edit",
				'icon' => 'edit',
			]) ?>
		<li>
			<?= html::a([
				'href' => "{$root_path}/delete/{$user->id}", 
				'text' => "Delete",
				'icon' => 'trash',
			]) ?>
		</li>
	</ul>
</div>
