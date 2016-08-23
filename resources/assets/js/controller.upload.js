angular.module('ft8')
.controller('UploadController', ['$scope', '$rootScope', '$http', 'Upload', function ($scope, $rootScope, $http, Upload) {

	$scope.init = function () {
		$scope.progressPct = 0;
		$scope.setStep(1);
		$scope.video = {};

		$scope.file = null;
	}

	$scope.setStep = function (step) {
		$scope.step = step;
	}

	$scope.setFile = function (file) {
		$scope.$error = null;

		if (file && file.size > 2147483648) {
			$scope.$error = $('#error_max_file_size').val();
		} else {
			$scope.file = file;
		}
	}

	$scope.setOverlay = function (file) {
		$scope.overlay = file;

		if (file)
			$scope.video.overlay = file.name;
		else
			$scope.video.overlay = null;
	}

	$scope.getFileName = function() {
		return $scope.file.name;
	}

	$scope.saveSetting = function () {
		var video = angular.copy($scope.video);

		video.status = 0;
		$scope.$saving = true;

		Upload.upload({
			url: $rootScope.getUrl('api/video/' + video._id),
			data: {
				file: $scope.overlay,
				video: $scope.video
			}
		}).then(function (response) {
		 	$scope.video = response.data;
		 	$scope.$saving = false;
        }, function (response) {
			$scope.$saving = false;
        });

	}

	$scope.uploadFile = function() {

		$scope.setStep(2);


		$http.post($rootScope.getUrl('api/video/'), $scope.video)
			 .success(function (response) {
			 	$scope.video = response;


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

		        			if ($scope.video.status == 3) {
		        				$scope.setStep(1);
		        				$scope.$error = $scope.video.errorMessages;
		        			}
		        		});
			        });
		        }, function (response) {
		        	$scope.setStep(1);
		        	$scope.$error = response;
		        }, function (evt) {
		            $scope.progressPct = parseInt(100.0 * evt.loaded / evt.total)
		        });
			 });

	}

	$scope.init ();

}])