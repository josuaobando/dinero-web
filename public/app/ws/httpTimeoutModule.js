'use strict';

/**
 * Global setting timeout of all AJAX calls
 */
angular.module('httpTimeoutModule', [])

	.provider('httpTimeout', function(){
		var self = this;
		this.config = {timeout: 10000};// default - 10 sec, in millis
		this.$get = function(){
			return {config: self.config};
		};
	})

	/**
	 * $http service decorator to add timeout
	 */
	.config(['$provide', function($provide){

		/**
		 * Configure $http provider to convert 'PUT', 'DELETE' methods to 'POST' requests
		 */
		$provide.decorator('$http', ['$delegate', 'httpTimeout', function($http, httpTimeout){

			// create function which overrides $http function
			var _$http = $http;
			$http = function(config){
				config.timeout = httpTimeout.config.timeout;
				return _$http(config);
			};
			$http.pendingRequests = _$http.pendingRequests;
			$http.defaults = _$http.defaults;

			// code copied from angular.js $HttpProvider function
			createShortMethods('get', 'delete', 'head', 'jsonp');
			createShortMethodsWithData('post', 'put');

			function createShortMethods(names){
				angular.forEach(arguments, function(name){
					$http[name] = function(url, config){
						return $http(angular.extend(config || {}, {
							method: name,
							url: url
						}));
					};
				});
			}

			function createShortMethodsWithData(name){
				angular.forEach(arguments, function(name){
					$http[name] = function(url, data, config){
						return $http(angular.extend(config || {}, {
							method: name,
							url: url,
							data: data
						}));
					};
				});
			}

			return $http;
		}]);

	}]);