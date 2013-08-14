angular.module('app').provide.factory('testFactory', function() {
	return {
		sayHi: function(text) {
			return "Hi " + text;
		},
		sayBye: function(text) {
			return "Bye " + text;
		}
	};
});
