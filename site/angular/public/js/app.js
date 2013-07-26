(function() {
	var app = angular.module('app', ['ui.bootstrap']); 
	// TODO: call individual sources in deps instead of loading entire bootstrap: https://github.com/angular-ui/bootstrap

	function depLoad($q, $rootScope, deps) { 
		var d = $q.defer();
		$LAB.script(deps).wait(function() { 
			if ($rootScope.$$phase) return d.resolve();
			$rootScope.$apply(function() { d.resolve(); }); 
		});
		return d.promise;
	}

	app.config(function(
		$routeProvider, 
		$httpProvider, 
		$locationProvider, 
		$controllerProvider,
		$compileProvider,
		$filterProvider,
		$provide
	) {

		app.controllerProvider = $controllerProvider;
		app.compileProvider    = $compileProvider;
		app.routeProvider      = $routeProvider;
		app.filterProvider     = $filterProvider;
		app.provide            = $provide;

		$locationProvider.html5Mode(true);
		
		$httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

		$routeProvider
		.when('/', { 
			templateUrl: '/a',
			resolve: {
				deps: function($q, $rootScope) {
					return depLoad($q, $rootScope, [
						'js/controller/main.test.js'
					]);
				}
			}
		})
		.when('/b', { 
			templateUrl: '/b',
			resolve: {
				deps: function($q, $rootScope) {
					return depLoad($q, $rootScope, [
						'js/controller/main.bar.js',
						'js/filter/numList.js',
						'js/filter/fuzzy.js'
					]);
				}
			}
		})
		.otherwise('/');
	});
}());
