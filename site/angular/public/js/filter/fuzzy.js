angular.module('app').filterProvider.register('fuzzy', function() {
	return function(items, search, type) {
		if (type === 'number')
			search = search.replace(/[^0-9]/g, '');
		var filtered = [];
		angular.forEach(items, function(item) {
			if (String(item).toLowerCase().indexOf(search.toLowerCase()) !== -1)
				filtered.push(item);
		});
		return filtered;
	};
});
