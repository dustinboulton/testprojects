angular.module('dealstruck').controller('DealsCtrl', ['$scope', 'userService', 'companyService',
	function($scope, userService, companyService) {

		$scope.user = userService.name;
		$scope.company = companyService.getCompany($scope.user);


		$scope.industrySelect = ['Banking', 'Eductation', 'Technology', 'Food Service', 'Health Care', 'Retail'];
		$scope.sectorSelect = ['Private', 'Government', 'Public'];
		$scope.yearsSelect = [1, 2, 5, 10];
		$scope.revenueMonthlySelect = ['0-10,000', '10,000-25,000', '25,000-50,000', '50,000-100,000', '100,000 +'];


		// TODO: abstract this out to a directive so it can be reused 
		$scope.addNewOwner = function() {
			// don't sumbit form - 
			event.preventDefault();

			// get current legnth and exit if > than 5
			var newItem = $scope.company.owners.length + 1;
			if (newItem > 5) return true;

			// else let's push a new owner - don't add ID so we can flag as a new entry
			$scope.company.owners.push({
				'name': 'New Owner'
			});

		};
		$(function() {
			$('.close').on("click", function() {
				$('#saved').hide();
			});
		});

		$scope.save = function() {

			// Would update model via service and api call - for now just pretend we saved :) 
			$scope.saved = true;
			$('#saved').show();
			companyService.setCompany($scope.company);
		}


	}
]);