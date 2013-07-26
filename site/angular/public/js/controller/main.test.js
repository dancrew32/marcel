angular.module('app').controllerProvider.register('main.test', function ($scope) {
	$scope.name = 'wat';	
	$scope.foo = function() {
		console.log($scope.name);
	};
	
	// toggle
	$scope.isCollapsed = false;

	// pager
	$scope.noOfPages   = 7;
	$scope.currentPage = 5;
	$scope.maxSize     = 5;
	$scope.setPage = function(pageNo) {
		$scope.currentPage = pageNo;
	};
});
