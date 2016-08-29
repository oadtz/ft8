angular.module('ft8')
.controller('GifViewController', ['$scope', '$rootScope', '$http', 'ngNotify', 'clipboard', function ($scope, $rootScope, $http, ngNotify, clipboard) {

	$scope.init = function () {
		//$http.get($rootScope.getMeta('thumbnail'));
		$scope.clipboard = clipboard;
	}

	$scope.setGif = function (gif) {
		$scope.gif = gif;

    	$rootScope.socket.subscribe('gif.' + $scope.gif._id).bind('updated', function(data){
    		if ($scope.gif.status != data.gif.status)
	    		$scope.$apply(function () {
	    			$scope.gif = data.gif;
	    		});
        });
	}

	$scope.copied = function () {
		ngNotify.set('Link copied to your clipboard.', 'success');
	}

	$scope.download = function (type) {
		window.location.href = $rootScope.getUrl('gif/' + $scope.gif._id + '/download/' + type);
	}

	$scope.init();

}])
.controller('GifGenerateController', ['$scope', '$rootScope', '$http', 'ngNotify', function ($scope, $rootScope, $http, ngNotify) {

	$scope.init = function () {

	}

	$scope.setGif = function (gif) {
		$scope.gif = gif;

    	$rootScope.socket.subscribe('gif.' + $scope.gif._id).bind('updated', function(data){
    		if ($scope.gif.status != data.gif.status)
	    		$scope.$apply(function () {
	    			$scope.gif = data.gif;

	    			if ($scope.gif.status === -1) {
	    				$scope.error();
	    			}
	    		});
        });
	}

	$scope.error = function (message) {
		ngNotify.set(message || 'Some thing went wrong. Please try again.', 'error');
	}

	$scope.generate = function () {
		$scope.$processing = true;

		$http.post($rootScope.getUrl('api/gif/' + $scope.gif._id + '/generate'), $scope.gif)
			.success(function (response) {
				if (response._id) {
					$scope.gif = response;
				}
			})
			.error(function () {
				$scope.error();
			});
	}

	$scope.cancel = function () {
		window.location.replace ($rootScope.getUrl('gif/upload'));
	}

	$scope.$watch('gif.status', function (status) {
		if (status > 1)
			$scope.$processing = true;
		else
			$scope.$processing = false;

		if (status == 4)
			window.location.replace($rootScope.getUrl('gif/' + $scope.gif._id + '.html'));

	});

	$scope.init();

}])
.controller('GifUploadController', ['$scope', '$rootScope', '$http', '$filter', '$interval', 'ngNotify', 'Upload', function ($scope, $rootScope, $http, $filter, $interval, ngNotify, Upload) {

	$scope.init = function () {
		$scope.file = null;
		$scope.progress = 0;
		$scope.gif = {
			status: 0
		};
	}

	$scope.error = function (message) {
		ngNotify.set(message || 'Some thing went wrong. Please try again.', 'error');

		$scope.init();
	}

	$scope.upload = function (file) {

		if (file && file.size > $rootScope.settings.max_file_size) {
			$scope.error('Please upload file smaller than ' + $filter('bytes')($rootScope.settings.max_file_size) + '. Your file is ' + $filter('bytes')(file.size));
		} else if (file) {
			$scope.file = file;

			$http.post($rootScope.getUrl('api/gif'))
				 .success(function (response) {
				 	if (response._id) {
				 		$scope.gif = response;

				        $scope.ngUpload = Upload.upload({
				            url: $rootScope.getUrl('api/gif/' + $scope.gif._id + '/upload'),
				            data: {
				            	file: $scope.file
				            }
				        });

				        $scope.ngUpload.then(function (response) {
				        	if (response.data) {
				        		$scope.gif = response.data;
				        		window.location.href = $rootScope.getUrl('gif/' + $scope.gif._id + '/generate');
				        	}
				        }, function (response) {
				        	if (response.status !== -1)
				        		$scope.error();
				        }, function (evt) {
				            $scope.progress = parseInt(100.0 * evt.loaded / evt.total)
				        });
				 	}
				 })
				 .error(function (response) {
				 	$scope.error();
				 });

		}
	}

	$scope.cancelUpload = function () {
		if ($scope.ngUpload)
			$scope.ngUpload.abort();

		$scope.init();
	}

	$scope.init();

}]);