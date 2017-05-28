'use strict';

angular.module('WSModule', [])

  .factory('WS', ['Connector', 'ClientConfig', function(Connector, ClientConfig){

    /**
     * Constructor function
     * @constructor
     */
    function WS(){

      /**
       * The last request made to the server
       * @type {}
       */
      this.lastRequest = null;

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
     * Returns the last state returned from the server
     * @returns string
     */
    WS.prototype.getResponseState = function(){
      var state = null;
      if(typeof this.lastResponse !== 'undefined'){
        state = this.lastResponse.state;
      }
      return state
    }

    /**
     * Returns the last user message returned from the server
     * @returns string
     */
    WS.prototype.getResponseUserMessage = function(){
      var userMessage = null;
      if(typeof this.lastResponse !== 'undefined'){
        userMessage = this.lastResponse.userMessage;
      }
      return userMessage
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
    }

    /**
     * Makes a request to the Backend Server
     * @param req
     * @param callback
     */
    WS.prototype.execPost = function(req, callback){

      // Add the required parameters to the request
      this.prepareRequest(req);

      // Store the last request
      this.lastRequest = req;

      var self = this;
      this.connector.post(req, function(res){

        // Store the response
        self.lastResponse = res;

        if(typeof res === 'undefined' || res === null){
          callback(null);
        } else{

          if (typeof res.response === 'object' && res.response !== null){
            callback(res.response);
          } else if(res.state === 'ok'){
            callback(true);
          } else{
            callback(null);
          }
        }
      });
    };

    /**
     * Adds the default parameters to the Backend request
     * @param req
     * @returns {*}
     */
    WS.prototype.prepareRequest = function(req){

      req.sys_access_pass = ClientConfig.sys_access_pass;
      req.userAgent = navigator.userAgent;
      req.isProxy = 1;

      if(ClientConfig.dev === true){
        req.XDEBUG_SESSION_START = 'ECLIPSE_DBGP';
      }
      if(!req.url){
        req.url = ClientConfig.wsController;
      }

    };

    return WS;

  }]);
