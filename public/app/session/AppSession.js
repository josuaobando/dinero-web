'use strict';

angular.module('AppSessionModule')

	.factory('AppSession', ['webStorage', function(webStorage){

		var AppSession = {};

		/**
		 * Dont Save in Memory
		 * @type {boolean}
		 */
		webStorage.memory.isSupported = false;

		/**
		 * Load object stored in the web store
		 * @param key {string} Key Name of the value to retrieve.
		 * @return {*} The value previously added under the specified key, else null.
		 */
		AppSession.get = function(key){
			var resObj;

			// Get item from Storage API
			var objStorage = webStorage.get(key, true);
			if(objStorage){
				// Parse JSON to object
				resObj = JSON.parse(objStorage);
			}

			return resObj;
		};

		/**
		 * Add object to web store
		 * @param objKey {string} Key Name of the value to retrieve.
		 * @param objValue (*) The value to add under the specified key.
		 * @returns {boolean}
		 */
		AppSession.set = function(objKey, objValue){
			var resObj = false;

			if(objValue){
				// Serialize Object to JSON
				var objStorage = JSON.stringify(objValue);
				// Set item over AppSession API
				resObj = webStorage.add(objKey, objStorage, true);
			}

			return resObj;
		};

		/**
		 * key Name of the value to remove.
		 * @param key {string} Key Name of the value to remove.
		 * @returns {boolean}
		 */
		AppSession.remove = function(key){
			return webStorage.remove(key, true);
		};

		/**
		 * Remove all values in the key/value web store.
		 *
		 * @return {boolean} True on success
		 */
		AppSession.clear = function(){
			return webStorage.clear(true);
		};

		return AppSession;
	}]);
