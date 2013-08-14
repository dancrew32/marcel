(function() {
	var CACHE_BUST = true;
	if (!Date.now) {
		Date.now = function now() {
			return new Date().getTime();
		};
	}
	var app = angular.module('app', ['ui.bootstrap']); 
	// TODO: call individual sources in deps instead of loading entire bootstrap: https://github.com/angular-ui/bootstrap

	var main = {
		test: { 
			templateUrl: '/a',
			resolve: {
				deps: function($q, $rootScope) {
					return depLoad($q, $rootScope, [
						'service/testService',
						'factory/testFactory',
						'controller/main.test'
					]);
				}
			}
		},
		circle: {
			templateUrl: '/circle',
			resolve: {
				deps: function($q, $rootScope) {
					return depLoad($q, $rootScope, [
						'vendor/vexflow/build/vexflow/vexflow-min',
						'factory/abcFactory',
						'controller/main.circle'
					]);
				}
			}
		},
		bar: {
			templateUrl: '/b',
			resolve: {
				deps: function($q, $rootScope) {
					return depLoad($q, $rootScope, [
						'directive/ngTap',
						'controller/main.bar',
						'filter/numList',
						'filter/fuzzy'
					]);
				}
			}
		}
	};

	function depLoad($q, $rootScope, deps) { 
		var d = $q.defer();
		var actual_deps = [];
		angular.forEach(deps, function(dep) {
			actual_deps.push('js/'+ dep +'.js'+ (CACHE_BUST ? ('?t='+ Date.now()) : ''));	
		});

		$LAB.script(actual_deps).wait(function() { 
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
		app.compileProvider    = $compileProvider; // directive
		app.routeProvider      = $routeProvider;
		app.filterProvider     = $filterProvider;
		app.provide            = $provide; // service, factory
		app.depLoad            = depLoad;

		$locationProvider.html5Mode(true);
		
		$httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

		$routeProvider
		.when('/', main.test)
		.when('/b', main.bar)
		.when('/circle', main.circle)
		.otherwise('/');
	});
}());
