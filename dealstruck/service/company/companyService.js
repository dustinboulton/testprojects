angular.module('dealstruck').factory('companyService', function() {
	var company;

	return {

		getCompany: function(userName) {
			if (!company) {
				return {
					'name': 'My Company Name',
					'ein': '',
					'industry': 'Food Service',
					'years': '',
					'sector': '',
					'revenue': [{
						'monthly': ''
					}, {
						'yearly': ''
					}],
					'owners': [{
						'name': 'test',
						'id': '123sd2345sfcas24ljhf'
					}, {
						'name': 'test2',
						'id': '123sd23dfg3445sfcas24'
					}]

				}
			} else {
				return company;
			}
		},

		setCompany: function(newCompany) {

			company = newCompany;

		}

	}
});