<fieldset class="field <?= $type ?><? echoif($range, ' has-range') ?><? if ($classes): ?> <?= implode(' ', $classes) ?><? endif ?>">
<? if ($label): ?>
<label for="<?= $id ?>">
	<?= $label ?>
</label>
<? endif ?>
<input
type="<?= $type ?>"
name="<?= $name ?>"
id="<?= $id ?>"
<? if ($size): ?>
maxlength="<?= $size ?>"
<? endif ?>
<? if ($minsize): ?>
minlength="<?= $minsize ?>"
<? endif ?>
<? foreach ($data as $k => $v): ?>
data-<?= $k ?>="<?= $v ?>"
<? endforeach ?>
<? if ($placeholder): ?>
	placeholder="<?= $placeholder ?>"
<? endif ?>
<? if ($type != 'password'): ?>
	value="<?= $value ?>"
<? endif ?>
<? if ($noauto): ?>
	autocomplete="off"
<? endif ?>
<? if ($disabled): ?>
	disabled="disabled"
<? endif ?>
<? if ($checked): ?>
	checked="checked"
<? elseif ($selected): ?> 
	selected="selected"
<? endif ?>>
<? if ($validate): ?>
<span class="validate">
	<?= $validate ?>
</span>
<? endif ?>
<? if ($range): ?>
<span class="range"
<? foreach ($range as $k => $v): ?>
data-<?= $k ?>="<?= $v ?>"
<? endforeach ?>></span>
<? endif ?>
<? if ($desc): ?>
<div class="desc">
	<?= $desc ?>
</div>
<? endif ?>
</fieldset>
