<fieldset class="field <?= $type ?><? if ($classes): ?> <?= implode(' ', $classes) ?>"<? endif ?>">
<? if ($label): ?>
<label for="<?= $id ?>">
	<?= $label ?>
</label>
<? endif ?>
<select
<? if ($multiple): ?>
	name="<?= $name ?>[]"
	multiple="multiple"
	size="<?= count($options) ?>"
<? else: ?>
	name="<?= $name ?>"
<? endif ?>
<? if ($disabled): ?>
	disabled="disabled"
<? endif ?>
id="<?= $id ?>">
<? foreach ($options as $k => $v): ?>
<? if ($multiple): ?>
<option value="<?= $k ?>"<? echoif(in_array($k, $value), ' selected="selected"') ?>><?= $v ?></option>
<? else: ?>
<option value="<?= $k ?>"<? echoif($value == $k, ' selected="selected"') ?>><?= $v ?></option>
<? endif ?>
<? endforeach ?>
</select>
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
