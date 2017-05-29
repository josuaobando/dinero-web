'use strict';

angular.module('ConnectorModule', ['httpTimeoutModule'])

	.config(['httpTimeoutProvider', '$httpProvider', 'ConfigProvider', function(httpTimeoutProvider, $httpProvider, ConfigProvider){
		httpTimeoutProvider.config.timeout = ConfigProvider.config.ws_timeout;
		$httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
	}])

	.factory('Connector', ['$http', function($http){

		function Connector(){

		}

		/**
		 * Posts a request to the Backend Server
		 *
		 * @param req [url: required]
		 * @param callback
		 */
		Connector.prototype.post = function(req, callback){

			var url = '';
			if(!req.url){
				callback(null);
				return;
			}else{
				url = req.url;
				delete req['url'];
			}

			$http.post(url, req)
				.success(function(data, status, headers, config){
					callback(data);
				})
				.error(function(data, status, headers, config){
					callback(null);
				});

		}

		return Connector;
	}]);