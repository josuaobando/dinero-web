'use strict'

// Declare app level module which depends on filters, and services
angular.module('DineroApp', ['pascalprecht.translate', 'ui.bootstrap', 'pasvaz.bindonce', 'ui.router', 'AppModule'])

	.run(['$rootScope', '$location', '$state', 'AppSession',
		function($rootScope, $location, $state, AppSession){

			$rootScope.$on('$locationChangeStart', function(event, toState, toParams){});

			$rootScope.$on('$locationChangeSuccess', function(event, toState, toParams){});

			$rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams){});

			$rootScope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams){});

		}]);

