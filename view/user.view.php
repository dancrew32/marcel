<div class="media-body">
	<h4 class="media-heading">
		<?= h($user->full_name()) ?>
		<small>
			<?= h($user->email) ?>
		</small>
		<span class="label pull-right">
			<? if ($user->active): ?>
				Active
			<? else: ?>
				Inactive
			<? endif ?>
		</span>
	</h4>
	<? /*
	<p>
		<strong>Script</strong>:
		<span class="text-<?= $script_class ?> word-wrap">
			<?= h($user->script) ?>
		</span>
	</p>
	*/ ?>
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
