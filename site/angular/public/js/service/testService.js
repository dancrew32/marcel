angular.module('app').provide.service('testService', function() {
	this.sayHi = function(text) {
		return "Hi " + text;
	};
});
