angular.module('dealstruck').controller('HeaderCtrl', ['$scope', 'userService',
	function ($scope,userService) {

		$scope.$watch(function () { return userService.isLoggedIn }, function (newVal, oldVal) {
		    if (typeof newVal !== 'undefined') {
		        $scope.isLoggedIn = userService.isLoggedIn;
		    }
		});

		$scope.isLoggedIn = userService.isLoggedIn;
		$scope.username = userService.username;


		
}]);