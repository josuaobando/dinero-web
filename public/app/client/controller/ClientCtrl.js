'use strict';

angular.module('ClientModule')

	.controller('ClientCtrl', ['$scope', '$state', 'InterfaceManager', 'ClientManager',
		function($scope, $state, InterfaceManager, ClientManager){

			$scope.changeUIState = function(state, UIParams){
				InterfaceManager.changeLocation(state, UIParams);
			};

			ClientManager.getCountries(function(countries){
				$scope.countriesData = countries;
			})

		}]);
