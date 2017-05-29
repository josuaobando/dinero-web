'use strict';

angular.module('WSModule', [])

	.factory('WS', ['Connector', 'ClientConfig', 'InterfaceManager', function(Connector, ClientConfig, InterfaceManager){

		/**
		 * Constructor function
		 * @constructor
		 */
		function WS(){

			/**
			 * The last response received from the server
			 * @type {}
			 */
			this.lastResponse = null;

			/**
			 * Connector instance used for communication
			 * @type {Connector}
			 */
			this.connector = new Connector();
		}

		/**
		 * Returns the last system message returned from the server
		 * @returns string
		 */
		WS.prototype.getResponseSystemMessage = function(){
			var systemMessage = null;
			if(typeof this.lastResponse !== 'undefined'){
				systemMessage = this.lastResponse.systemMessage;
			}
			return systemMessage
		};

		/**
		 * Makes a request to the Backend Server
		 * @param req
		 * @param callback
		 */
		WS.prototype.execPost = function(req, callback){

			// Add the required parameters to the request
			this.prepareRequest(req);

			var self = this;
			this.connector.post(req, function(res){

				// Store the response
				self.lastResponse = res;

				if(typeof res === 'undefined' || res === null){
					callback(null);
				}else{

					if(typeof res.response === 'object' && res.response !== null){
						callback(res.response);
					}else if(res.state === 'ok'){
						callback(true);
					}else{
						callback(null);
					}
				}
			});
		};

		/**
		 * Adds the default parameters to the Backend request
		 *
		 * @param req
		 * @returns {*}
		 */
		WS.prototype.prepareRequest = function(req){

			req.format = 'json';
			req.userAgent = navigator.userAgent;
			req.url = ClientConfig.wsController;
			req.auth = ClientConfig.auth;
			req.sid = InterfaceManager.getSid();

			if(ClientConfig.dev === true){
				req.XDEBUG_SESSION_START = 'ECLIPSE_DBGP';
			}

		};

		return WS;

	}]);
