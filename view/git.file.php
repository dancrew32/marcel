<div class="git-file">
	<?= $path ?>
	<? if ($stage): ?>
		<a href="<?= $stage ?>" class="btn pull-right" style="margin-top: -5px;">
			<i class="icon-plus-sign"></i>
			Stage
		</a>
	<? endif ?>
	<? if ($unstage): ?>
		<a href="<?= $unstage ?>" class="btn pull-right" style="margin-top: -5px;">
			<i class="icon-minus-sign"></i>
			Unstage
		</a>
	<? endif ?>

</div>
