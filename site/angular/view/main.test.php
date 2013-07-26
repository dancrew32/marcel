<div ng-controller="<?= $ctrl ?>">
	<?= $form ?>
	<span>Hi {{name | currency}}</span>

	<button ng-click="foo()">Click Plz</button>

	<button class="btn" ng-click="isCollapsed = !isCollapsed">Toggle collapse</button>
	<div collapse="isCollapsed">
		<div class="well">Some content</div> 
	</div>


	<div class="well well-small">
		<h4>Default</h4>

		<pagination num-pages="noOfPages" current-page="currentPage"></pagination>
		<pagination boundary-links="true" num-pages="noOfPages" current-page="currentPage" class="pagination-small" previous-text="'&lsaquo;'" next-text="'&rsaquo;'" first-text="'&laquo;'" last-text="'&raquo;'"></pagination>
		<pagination direction-links="false" boundary-links="true" num-pages="noOfPages" current-page="currentPage"></pagination>
		<pagination direction-links="false" num-pages="noOfPages" current-page="currentPage"></pagination>
		<button class="btn" ng-click="setPage(3)">Set current page to: 3</button>
		The selected page no: {{currentPage}}
		<pager num-pages="noOfPages" current-page="currentPage"></pager>
	</div>

</div>
