'use strict';

angular.module('AppModule', ['httpTimeoutModule'])

  .config(['httpTimeoutProvider', '$httpProvider', 'AppConfigProvider', function(httpTimeoutProvider, $httpProvider, AppConfigProvider) {
    httpTimeoutProvider.config.timeout = AppConfigProvider.config.ws_timeout;
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
  }])

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