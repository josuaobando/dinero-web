'use strict';

angular.module('SystemModule')

  .controller('SystemCtrl', ['$scope', '$location', '$state', '$stateParams', 'InterfaceManager', 'ClientManager',
    function($scope, $location, $state, $stateParams, InterfaceManager, ClientManager){

      $scope.changeUIState = function(state, UIParams){
        InterfaceManager.changeLocation(state, UIParams);
      };

  }]);
