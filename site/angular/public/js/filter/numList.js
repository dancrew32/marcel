angular.module('app').filterProvider.register('numList', function() {
	var tests = {
		even: function(item) {
			if (item % 2 === 0)
				return item;
		},
		odd: function(item) {
			if (item % 2 !== 0)
				return item;
		},
		lucky: function(item) {
			if (item === 7 || item === 11)
				return item;
		}
	};
	return function(items, type) {
		var filtered = [];
		angular.forEach(items, function(item) {
			if (tests[type](item))
				filtered.push(item);
		});
		return filtered;
	};
});
