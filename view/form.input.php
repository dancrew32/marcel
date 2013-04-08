<div class="control-group">
<? if ($label): ?>
	<label<? echoif(isset($attrs['id']), " for=\"{$attrs['id']}\"") ?>
	class="control-label<? echoif(take($attrs, 'inline', false), ' inline') ?>">
	<?= $label ?>
	</label>
<? endif ?>
	<div class="controls">
		<?= form::input($attrs) ?>
	</div>
</div>
