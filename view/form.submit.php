<fieldset 
	class="field <?= $type ?><? if ($classes): ?> <?= implode(' ', $classes) ?>"<? endif ?>">
<button type="submit"
<? if ($disabled): ?>
	disabled="disabled"
<? endif ?>>
	<?= $text ?>
</button>
</fieldset>
