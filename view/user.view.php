<div class="media-body">
	<h4 class="media-heading">
		<? if (isset($user->active)): ?>
			<?= $user->badge('pull-right') ?>
		<? endif ?>

		<?= h(ifset($user->full_name(), $user->username, "No Name")) ?>

		<? if (isset($user->email{0})): ?>
			<small>
				<?= h($user->email) ?>
			</small>
		<? endif ?>
	</h4>
	<ul class="nav nav-pills last">
		<? if ($mode != 'edit'): ?>
			<li>
				<?= html::a([
					'href' => "{$root_path}/edit/{$user->id}", 
					'text' => "Edit",
					'icon' => 'edit',
				]) ?>
			<li>
		<? endif ?>
			<?= html::a([
				'href' => "{$root_path}/delete/{$user->id}", 
				'text' => "Delete",
				'icon' => 'trash',
			]) ?>
		</li>
	</ul>
</div>
