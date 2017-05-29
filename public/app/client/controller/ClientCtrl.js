'use strict';

angular.module('ClientModule')

	.controller('ClientCtrl', ['$scope', '$state', 'InterfaceManager', 'ClientManager',
		function($scope, $state, InterfaceManager, ClientManager){

			$scope.$watch('sid', function(newValue, oldValue){
				if(newValue && newValue != oldValue){
					$scope.sid = newValue;
				}
			});

			$scope.changeUIState = function(state, UIParams){
				InterfaceManager.changeLocation(state, UIParams);
			};

			$scope.countriesData = [];

			ClientManager.getCountries(function(countries){
				$scope.countriesData = countries;
			})

		}]);
