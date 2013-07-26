angular.module('app').controllerProvider.register('main.bar', function ($scope, $http, $filter) {
	$http.get('/data', { cache: true }).success(function(json) {
		$scope.letters = json;
	});
	$scope.listFilter = function() {
		var result = $scope.letters;
		if ($scope.odd)
			result = $filter('numList')(result, 'odd');
		if ($scope.even)
			result = $filter('numList')(result, 'even');
		if ($scope.lucky)
			result = $filter('numList')(result, 'lucky');
		if ($scope.search)
			result = $filter('fuzzy')(result, $scope.search, 'number');
		return result;
	};
});
