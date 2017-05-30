'use strict';

angular.module('ClientModule', [])

	.factory('ClientManager', ['WS', 'InterfaceManager', function(WS, InterfaceManager){

		var ClientManager = {};

		/**
		 * get countries
		 *
		 * @param callback
		 */
		ClientManager.getCountries = function(callback){
			InterfaceManager.getFilter('countries', function(filterData){
				if(filterData){

					callback(filterData);

				}else{

					var req = {f: "getCountries"};
					var WSBEConnector = new WS();
					WSBEConnector.execPost(req, function(res){

						if(res === {} || res === null){
							callback([], WSBEConnector.getResponseSystemMessage());
						}else if(res.hasOwnProperty('countries')){
							var countries = res.countries;

							InterfaceManager.addFilter('countries', countries);

							callback(countries);
						}else{
							callback([]);
						}

					});

				}
			});
		};

		/**
		 * get agencies
		 *
		 * @param callback
		 */
		ClientManager.getAgencies = function(callback){
			InterfaceManager.getFilter('agencies', function(filterData){
				if(filterData){

					callback(filterData);

				}else{

					var req = {f: "getAgencies"};
					var WSBEConnector = new WS();
					WSBEConnector.execPost(req, function(res){

						if(res === {} || res === null){
							callback([], WSBEConnector.getResponseSystemMessage());
						}else if(res.hasOwnProperty('agencies')){
							var agencies = res.agencies;

							InterfaceManager.addFilter('agencies', agencies);

							callback(agencies);
						}else{
							callback([]);
						}

					});

				}
			});
		};

		return ClientManager;
	}]);