<div class="container" ng-controller="<?= $ctrl ?>">
	<div class="row">
		<div class="span4">
			<?= $form ?>
		<div ng-tap="tapped()">Tap</div>
		</div>
		<ul class="span4 numList">
			<li ng-repeat="letter in listFilter()">{{letter}}</li>
		</ul>
	</div>
</div>
