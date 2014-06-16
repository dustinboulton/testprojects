angular.module('dealstruck').controller('SidebarCtrl', ['$scope', 'userService', 'companyService',
	function($scope, userService, companyService) {
	

	$scope.steps = {'deals': 'Basic Company Information',
					'deals.owners': 'About the Owners',
					'deals.goals': 'Goals for this loan',
					'deals.xit' : 'Finished',
					};

	$scope.offer = {'name': 'The Money Company',
					'disclaimer': 'You only get some if you give some',
					'terms': '50% APR if you fault on one payment'
					};

	$scope.user = userService.name;
	$scope.company = companyService.getCompany($scope.user);



}]);