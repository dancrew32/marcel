angular.module('app').compileProvider.directive('ngTap', function() {
	return function(scope, element, attrs) {
		var tapping = false;
		var active = 'active';
		element.bind('touchstart', function(e) {
			element.addClass(active);
			tapping = true;
		});
		element.bind('touchmove', function(e) {
			element.removeClass(active);
			tapping = false;
		});
		element.bind('touchend', function(e) {
			element.removeClass(active);
			if (tapping)
				scope.$apply(attrs['ngTap'], element);
		});
	};
});
