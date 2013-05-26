<div class="media-body">
	<h4 class="media-heading">
		<? if (isset($user->active)): ?>
			<?= $user->badge('pull-right') ?>
		<? endif ?>

		<?= ifset($user->full_name(), $user->username, "No Name") ?>

		<? if (isset($user->email{0})): ?>
			<small>
				<?= $user->email ?>
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
			</li>
		<? endif ?>
		<li>
			<?= html::a([
				'href' => "{$root_path}/delete/{$user->id}", 
				'text' => "Delete",
				'icon' => 'trash',
			]) ?>
		</li>
		<? if (!$user->verified): ?>
			<li>
				<?= html::a([
					'href' => app::get_path('User Verification Resend')."/{$user->id}?r={$root_path}",
					'text' => "Retry Verification",
					'icon' => 'envelope',
				]) ?>
			</li>
		<? endif ?>
	</ul>
</div>
