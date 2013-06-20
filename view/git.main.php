<div class="row">
	<div class="span8">

		<h3>Commit</h3>
		<?= r('git', 'commit_form') ?>

		<h3>Origin</h3>
		<?= r('git', 'origin') ?>

		<h3>Log</h3>
		<?= r('git', 'log_simple') ?>

	</div>
	<div class="span4">

		<?= r('git', 'status') ?>

	</div>
</div>
