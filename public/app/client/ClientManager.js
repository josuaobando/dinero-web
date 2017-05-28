'use strict';

angular.module('ClientModule', [])

  .factory('ClientManager', ['WS', '$filter', 'InterfaceManager', function(WS, $filter, InterfaceManager){

    var ClientManager = {};

    return ClientManager;
  }]);