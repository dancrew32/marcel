<fieldset class="field <?= $type ?><? if ($classes): ?> <?= implode(' ', $classes) ?>"<? endif ?>">
<? if ($label): ?>
<label for="<?= $id ?>">
	<?= $label ?>
</label>
<? endif ?>
<textarea
name="<?= $name ?>"
class="<?= implode(' ', $classes) ?>"
<? if ($disabled): ?>
	disabled="disabled"
<? endif ?>
<? if ($placeholder): ?>
	placeholder="<?= $placeholder ?>"
<? endif ?>
id="<?= $id ?>"><?= $value ?></textarea>
<? if ($validate): ?>
	<span class="validate"></span>
<? endif ?>
<? if ($validate): ?>
<span class="validate">
	<?= $validate ?>
</span>
<? endif ?>
<? if ($desc): ?>
<div class="desc">
	<?= $desc ?>
</div>
<? endif ?>
</fieldset>
