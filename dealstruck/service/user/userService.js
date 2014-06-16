angular.module('dealstruck')
	.service('userService', ['$location', 
	function($location) {
		
	// Would call an API here to populate currently logged in user.

	if ($location.path() != '/home') {
		this.name = 'Dustin';
		this.username = 'Dustin';
		this.email = 'dustin.boulton@gmail.com';
		this.isLoggedIn = true;
	} else {
		this.isLoggedIn = false;
	}
	return this;
}]);