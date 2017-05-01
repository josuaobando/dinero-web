'use strict';

angular.module('AppConnectorModule', [])

  .factory('AppConnector', ['$http', 'AppConfig', function($http, AppConfig){

  /**
   * @constructor
   */
  function AppConnector(){}

    /**
     * Posts a request
     *
     * @param req [url: required]
     * @param callback
     */
    AppConnector.prototype.post = function(req, callback){

      var url = AppConfig.ws;
      $http.post(url, req)
        .success(function(data, status, headers, config){
          callback(data);
        })
        .error(function(data, status, headers, config){
          console.log("Connection Error: Status: " + status + " Data: " + data);
          callback(null);
        });

    };

    return AppConnector;
  }]);