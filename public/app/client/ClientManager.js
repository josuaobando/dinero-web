'use strict';

angular.module('ClientModule', [])

	.factory('ClientManager', ['WS', function(WS){

		var ClientManager = {};

		ClientManager.getCountries = function(callback){

			var req = {f: "getCountries"};

			var WSBEConnector = new WS();
			WSBEConnector.execPost(req, function(res){

				if(res === {} || res === null){
					callback([], WSBEConnector.getResponseUserMessage());
				}else if(res.hasOwnProperty('countries')){
					var countries = res.countries;
					callback(countries);
				}else{
					callback([]);
				}

			});

		};

		return ClientManager;
	}]);