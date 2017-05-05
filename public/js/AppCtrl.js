'use strict';

angular.module('AppModule', [])

  .controller('AppCtrl', ['$scope', 'AppSession', function($scope, AppSession){

    $scope.$watch('sid', function(value) {
      if(value){
        AppSession.set('sid', value);
      }else{
        $scope.sid = AppSession.get('sid');
      }
    });

  }]);
