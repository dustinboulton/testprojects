angular.module('dealstruck', ['ui.bootstrap', 'ui.utils', 'ui.router', 'ngAnimate']);

angular.module('dealstruck').config(function($stateProvider, $urlRouterProvider) {

        $stateProvider
             .state('home', {
                    url: '/home',
                    views: {
                        'content': {
                            templateUrl: 'partial/home/home.html',
                            controller: 'DealsCtrl'
                        },
                    }
                })
             .state('deals', {
                    url: '/deals',
                    views: {
                        'content': {
                            templateUrl: 'partial/deals/deals.html',
                            controller: 'DealsCtrl'
                        },
                    }
                })
                .state('deals.goals', {
                        url: '/goals',
                        parent: 'deals',
                        views: {
                            'main': {
                                templateUrl: 'partial/goals/goals.html',
                                controller: 'GoalsCtrl'
                            }
                        }
                
                })
                .state('deals.owners', {
                        url: '/owners',
                        parent: 'deals',
                        views: {
                            'main': {
                                templateUrl: 'partial/owners/owners.html',
                                controller: 'DealsCtrl'
                            }
                        }
                
                });
    /* Add New States Above */
    $urlRouterProvider.otherwise('/home');

});

angular.module('dealstruck').run(function($rootScope) {

    $rootScope.safeApply = function(fn) {
        var phase = $rootScope.$$phase;
        if (phase === '$apply' || phase === '$digest') {
            if (fn && (typeof(fn) === 'function')) {
                fn();
            }
        } else {
            this.$apply(fn);
        }
    };

});