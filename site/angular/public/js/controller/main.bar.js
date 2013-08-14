angular.module('app').controllerProvider.register('main.bar', function (
	$scope, 
	$http, 
	$filter
) {
	$http.get('/data', { cache: true }).success(function(json) {
		$scope.letters = json;
	});
	$scope.tapped = function() {
		alert('tapped');
	};
	$scope.listFilter = function() {
		var result     = $scope.letters;
		var falsify    = [];
		var numFilters = [];

		if ($scope.odd)
			numFilters.push('odd');
		if ($scope.even)
			numFilters.push('even');
		if ($scope.lucky)
			numFilters.push('lucky');

		// Number filter
		angular.forEach(numFilters, function(filter) {
			result = $filter('numList')(result, filter);
		});

		// Type search
		if ($scope.search)
			result = $filter('fuzzy')(result, $scope.search, 'number');

		return result;
	};
});
