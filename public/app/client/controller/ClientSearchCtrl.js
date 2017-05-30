'use strict';

angular.module('ClientModule')

	.controller('ClientSearchCtrl', ['$scope', '$filter', 'ClientManager',
		function($scope, $filter, ClientManager){

			$scope.reportFilter = {
				filterAgencyTypeId: 0,
				filterAgencyId: ""
			};

			$scope.agenciesFilterData = [];
			$scope.getAgenciesByType = function(){

				$scope.agenciesFilterData = [];
				$scope.reportFilter.filterAgencyId = 0;

				ClientManager.getAgencies(function(agencies){
					$scope.agenciesFilterData = $filter('filter')(agencies, function(itemData){
						return (itemData.AgencyType_Id == $scope.reportFilter.filterAgencyTypeId);
					}, true);
				});

			};

		}]);
