angular.module('ft8')
.controller('UploadController', ['$scope', '$rootScope', '$http', 'Upload', function ($scope, $rootScope, $http, Upload) {

	$scope.init = function () {
		$http.get($rootScope.getUrl('api/video/current'))
		     .success(function (response) {
		     	$scope.video = response;
		     });
	}

	$scope.setStep = function (step) {
		$scope.step = step;
	}

	$scope.setFile = function (file) {
		$scope.$error = null;
		$scope.video = null;

		if (file && file.size > $rootScope.settings.max_file_size) {
			$scope.$error = $('#error_max_file_size').val();
		} else {
			$scope.file = file;
		}
	}

	$scope.getFileName = function() {
		return $scope.file.name;
	}

	$scope.getUrl = function () {
		return $scope.video.url;
	}

	$scope.saveSetting = function () {
		$scope.$saving = true;

		$http.post($rootScope.getUrl('api/video/' + $scope.video._id), $scope.video)
			 .success(function (response) {
		 		$scope.video = response;
		 		$scope.$saving = false;
			 })
			 .error(function (response) {
			 	$scope.$saving = false;
			 });
	}

	$scope.uploadFile = function() {
		$scope.video = {
			status: 0
		};
		$http.post($rootScope.getUrl('api/video/'), $scope.video)
			 .success(function (response) {
			 	$scope.video = response;

				$scope.progressPct = 0;
		        Upload.upload({
		            url: $rootScope.getUrl('api/video/' + $scope.video._id + '/upload'),
		            data: {
		            	file: $scope.file,
		            	video: $scope.video
		            }
		        }).then(function (response) {
		        	$rootScope.socket.subscribe('video.' + response.data._id).bind('updated', function(data){
		        		//console.log (data);
		        		$scope.$apply(function () {
		        			$scope.video = data.video;

		        			if ($scope.video.status == 4) {
		        				$scope.$error = $scope.video.errorMessages;
								$scope.file = null;
								$scope.video = {};
		        			}
		        		});
			        });
		        }, function (response) {
		        	$scope.$error = response;
					$scope.file = null;
					$scope.video = {};
		        }, function (evt) {
		            $scope.progressPct = parseInt(100.0 * evt.loaded / evt.total)
		        });
			 });

	}

	$scope.$watch('video.status', function (status) {
		switch (status) {
			case 0:
			case 1:
			case 2:
				$scope.setStep(2);
				break;
			case 3:
				$scope.setStep(3);
				break;
			default:
				$scope.setStep(1);
		}
	});

	$scope.init ();

}])