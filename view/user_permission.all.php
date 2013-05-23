<div class="row">
	<div class="span8">

		<div class="clearfix">
			<h1 class="pull-left">
				User Permissions
			</h1>
		</div>

		<form action="<?= $action ?>" method="post">
			<table class="table table-condensed table-hover table-striped">
				<thead>
					<tr>
						<th>
							Feature
						</th>
						<? foreach ($user_types as $user_type): ?>
							<th>
								<?= $user_type ?>
							</th>
						<? endforeach ?>
					</tr>
				</thead>
				<tbody>
					<? foreach ($features as $feature): ?>
						<tr<? echoif(auth::can([$feature->slug]), ' class="success"') ?>>
							<td>
								<?= $feature ?>
								<?= html::a(app::get_path('Feature Home')."/edit/{$feature->id}", 'Edit', 'edit') ?>
							</td>
							<? foreach ($user_types as $user_type): ?>
								<td class="text-center">
									<?= new field('checkbox', [
										'name'    => "{$feature->id}|{$user_type->id}",
										'value'   => 1,
										'checked' => User_Permission::find('first', [
											'conditions' => [ 
												'feature_id = ? and user_type_id = ?',
												$feature->id, $user_type->id, 
											],
										]),
									]) ?>
								</td>
							<? endforeach ?>
						</tr>
					<? endforeach ?>
				</tbody>
			</table>
			<?= new field('submit', [ 'text' => 'Update' ]) ?>
		</form>

	</div>

	<div class="span4">
		<ul class="nav nav-tabs nav-stacked">
			<li>
				<?= html::a(app::get_path('Feature Home'), 'View All Features', 'eye-open') ?>
			</li>
		</ul>

		<? pp(User_Permission::$instance) ?>
	</div>
</div>
