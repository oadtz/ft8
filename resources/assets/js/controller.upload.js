angular.module('ft8')
.controller('UploadController', ['$scope', '$rootScope', function ($scope, $rootScope) {

	$scope.init = function () {
		$scope.setStep(2);
	}

	$scope.setStep = function (step) {
		$scope.step = step;
	}

	$scope.init ();

}])