<div ng-controller="<?= $ctrl ?>">
	<?= $form ?>
	<ul>
		<li ng-repeat="letter in listFilter()">{{letter}}</li>
	</ul>
</div>
