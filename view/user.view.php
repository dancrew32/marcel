<div class="media-body">
	<h4 class="media-heading">
		<?= h($user->full_name()) ?>
		<? if (isset($user->email{0})): ?>
			<small>
				<?= h($user->email) ?>
			</small>
		<? endif ?>

		<? if (isset($user->active)): ?>
			<span class="label pull-right">
				<? if ($user->active): ?>
					Active
				<? else: ?>
					Inactive
				<? endif ?>
			</span>
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
