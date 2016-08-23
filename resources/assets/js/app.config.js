angular.module('ft8')
.run(['$rootScope', function ($rootScope) {
	
	$rootScope.init = function () {
		/*if (broadcastUrl = $rootScope.getMeta('broadcast_url')) {
			$rootScope.socket = io(broadcastUrl);
		}*/
		$rootScope.socket = new Pusher($rootScope.getMeta('broadcast_url'), {
	      cluster: 'ap1',
	      encrypted: true
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