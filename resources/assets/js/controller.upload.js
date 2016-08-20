angular.module('ft8')
.controller('UploadController', ['$scope', '$rootScope', function ($scope, $rootScope) {

	$scope.init = function () {
		$scope.setStep(1);
		$scope.file = null;
	}

	$scope.setStep = function (step) {
		$scope.step = step;
	}

	$scope.setFile = function (file) {
		$scope.file = file;
	}

	$scope.removeFile = function () {
		$scope.file = null;
	}

	$scope.init ();

}])