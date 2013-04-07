<? if ($label): ?>
<label<? echoif(isset($attrs['id']), " for=\"{$attrs['id']}\"") ?>
<? echoif(take($attrs, 'inline', false), 'class="inline"') ?>>
<?= $label ?>
</label>
<? endif ?>
<?= form::input($attrs) ?>
