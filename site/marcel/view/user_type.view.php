<div class="media-body">
	<h4 class="media-heading">
		<?= $user_type ?>
		<small>
			(<?= $user_type->slug ?>)
		</small>
	</h4>
	<ul class="nav nav-pills last">
		<? if ($mode != 'edit'): ?>
			<li>
				<?= html::a([
					'href' => route::get('User Type Edit', ['id' => $user_type->id]),
					'text' => "Edit",
					'icon' => 'edit',
				]) ?>
			<li>
		<? endif ?>
			<?= html::a([
				'href' => route::get('User Type Delete', ['id' => $user_type->id]),
				'text' => "Delete",
				'icon' => 'trash',
			]) ?>
		</li>
	</ul>
</div>
