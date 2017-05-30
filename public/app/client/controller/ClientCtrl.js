'use strict';

angular.module('ClientModule')

	.controller('ClientCtrl', ['$scope', '$state', 'InterfaceManager', 'ClientManager',
		function($scope, $state, InterfaceManager, ClientManager){

			InterfaceManager.getSid();

			$scope.changeUIState = function(state, UIParams){
				InterfaceManager.changeLocation(state, UIParams);
			};

			$scope.countriesData = [];
			ClientManager.getCountries(function(countries){
				$scope.countriesData = countries;
			});

			$scope.agenciesData = [];
			ClientManager.getAgencies(function(agencies){
				$scope.agenciesData = agencies;
			});

		}]);
