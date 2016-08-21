angular.module('ft8')
.controller('UploadController', ['$scope', '$rootScope', 'Upload', function ($scope, $rootScope, Upload) {

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

	$scope.getFileName = function() {
		return $scope.file.name;
	}

	$scope.uploadFile = function() {

		$scope.setStep(2);
        Upload.upload({
            url: 'api/video/upload',
            data: {
            	file: $scope.file,
            	video: $scope.video
            }
        }).then(function (response) {
        	window.socket.on('video' + response.data._id + ':updated', function(video){
        		console.log (video);
	        });
        }, function (response) {
        	$scope.setStep(1);
        	$scope.$error = response;
        }, function (evt) {
            $scope.progressPct = parseInt(100.0 * evt.loaded / evt.total)
        });

	}

	$scope.init ();

}])