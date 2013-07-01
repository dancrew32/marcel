<div class="git-file well well-small alert-<?= $color_class ?>"
	data-toggle="popover" 
	data-placement="bottom" 
	<?= isset($title{0}) ? " data-content=\"{$title}>\"" : '' ?>>

	<?= $path_trunc ?>

	<? if ($stage): ?>
		<? if (isset($reset)): ?>
			<a href="<?= $reset ?>" class="btn pull-right" style="margin-top: -5px;">
				<i class="icon-remove-sign"></i>
			</a>
		<? endif ?>
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
