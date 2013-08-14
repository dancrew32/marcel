angular.module('app').controllerProvider.register('main.test', 
	function (
		$scope, 
		testService,
		testFactory,
		$dialog
	) {
	$scope.name = testService.sayHi('wat');	
	$scope.name = testFactory.sayHi('okay');
	$scope.foo = function() {
		$scope.alerts.push({
			msg: "you can't do that!"
		});
	};
	
	// toggle
	$scope.isCollapsed = false;

	// pager
	$scope.noOfPages   = 5;
	$scope.currentPage = 5;
	$scope.maxSize     = 5;
	$scope.setPage = function(pageNo) {
		$scope.currentPage = pageNo;
	};

	// alert
	$scope.alerts = [
		{ 
			type: 'error', 
			msg: 'There was an error',
		},
		{ 
			type: 'success', 
			msg: 'That worked!',
		}
	];
	$scope.closeAlert = function(index) {
		$scope.alerts.splice(index, 1);
	};
	$scope.addAlert = function() {
		$scope.alerts.push({
			msg: 'another.'	
		});
	};


	// buttons
	$scope.singleModel = 1;
	$scope.singleModelChange = function() {
		console.log($scope.singleModel ? 'YEAH!' : 'AWWW');
	};


	// Date picker
	$scope.today = function() {
		$scope.dt = new Date();
	};
	$scope.today();

	$scope.showWeeks = true;
	$scope.toggleWeeks = function () {
		$scope.showWeeks = !$scope.showWeeks;
	};

	$scope.clear = function () {
		$scope.dt = null;
	};

	// Disable weekend selection
	$scope.disabled = function(date, mode) {
		return ( mode === 'day' && ( date.getDay() === 0 || date.getDay() === 6 ) );
	};

	$scope.toggleMin = function() {
		$scope.minDate = ( $scope.minDate ) ? null : new Date();
	};
	$scope.toggleMin();


	// Modal
	$scope.modalOpen = function() {
		$dialog.dialog({
			backdrop: true,
			keyboard: true,
			templateUrl: '/c',
			controller: function($scope, dialog) {
				$scope.close = function(mode) {
					dialog.close();	
					switch (mode) {
						case 'okay':
							console.log('action performed!');
						break;
					}
				};
			}
		}).open();
	};

});
