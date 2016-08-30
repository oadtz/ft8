angular.module('ft8')
.run(['$rootScope', '$http', 'ngNotify', function ($rootScope, $http, ngNotify) {
	
	$rootScope.init = function () {
		/**/
		if ($rootScope.getMeta('broadcast_token')) {
			$rootScope.pusher = new Pusher($rootScope.getMeta('broadcast_token'), {
		      cluster: 'ap1',
		      encrypted: true
		    });
		} else if (broadcastUrl = $rootScope.getMeta('broadcast_url')) {
			$rootScope.socket = io(broadcastUrl);
		}

		$http.get($rootScope.getUrl('api/settings'))
			 .success(function (response) {
			 	$rootScope.settings = response;
			 });

		ngNotify.config({
		    theme: 'pure',
		    position: 'top',
		    duration: 3000,
		    type: 'info',
		    sticky: false,
		    button: true,
		    html: true
		});
	}

	$rootScope.getMeta = function (meta) {
		return $('meta[name='+meta+']').attr('content');
	}

	$rootScope.getUrl = function (path, params) {
		var url = $('base').attr('href') + '/' + path;

		if (params)
			url += '?' + $.param(params);

		return url;
	}

	$rootScope.init();

}])
.filter('bytes', [function() {
	return function(bytes, precision) {
		if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return '-';
		if (typeof precision === 'undefined') precision = 1;
		var units = ['bytes', 'kB', 'MB', 'GB', 'TB', 'PB'],
			number = Math.floor(Math.log(bytes) / Math.log(1024));
		return (bytes / Math.pow(1024, Math.floor(number))).toFixed(precision) +  ' ' + units[number];
	}
}]);