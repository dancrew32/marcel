<div class="git-file"<?= isset($title{0}) ? " title=\"{$title}\"" : '' ?>>
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
