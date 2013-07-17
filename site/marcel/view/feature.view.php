<div class="media-body">
	<h4 class="media-heading">
		<?= $feature ?>
		<small>
			(<?= $feature->slug ?>)
		</small>
	</h4>
	<ul class="nav nav-pills last">
		<? if ($mode != 'edit'): ?>
			<li>
				<?= html::a([
					'href' => route::get('Feature Edit', [ 'id' => $feature->id ]), 
					'text' => "Edit",
					'icon' => 'edit',
				]) ?>
			<li>
		<? endif ?>
			<?= html::a([
				'href' => route::get('Feature Delete', [ 'id' => $feature->id ]), 
				'text' => "Delete",
				'icon' => 'trash',
			]) ?>
		</li>
	</ul>
</div>
