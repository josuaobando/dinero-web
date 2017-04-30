'use strict';

angular.module('AppModule', [])

  .factory('AppWS', ['AppConnector', 'AppConfig', function(AppConnector, AppConfig){

    /**
     * Constructor function
     *
     * @constructor
     */
    function AppWS(){

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
       *
       * @type AppConnector
       */
      this.appConnector = new AppConnector();
    }

    /**
     * Returns the last state returned from the server
     * @returns string
     */
    AppWS.prototype.getResponseState = function(){
      var state = null;
      if(typeof this.lastResponse !== 'undefined'){
        state = this.lastResponse.state;
      }
      return state
    };

    /**
     * Returns the last user message returned from the server
     * @returns string
     */
    AppWS.prototype.getResponseUserMessage = function(){
      var userMessage = null;
      if(typeof this.lastResponse !== 'undefined'){
        userMessage = this.lastResponse.userMessage;
      }
      return userMessage
    };

    /**
     * Returns the last system message returned from the server
     * @returns string
     */
    AppWS.prototype.getResponseSystemMessage = function(){
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
    AppWS.prototype.execPost = function(req, callback){

      // Add the required parameters to the request
      this.prepareRequest(req);

      // Store the last request
      this.lastRequest = req;

      var self = this;
      this.appConnector.post(req, function(res){

        // Store the response
        self.lastResponse = res;

        if(typeof res === 'undefined' || res === null){
          callback(null);
        } else{

          if (typeof res.response === 'object' && res.response !== null){
            callback(res.response);
          } else if(res.state === 'ok'){
            callback(true);
          } else if(res.state === 'expired'){
            console.log('Expired Session')
          } else{
            callback(null);
          }
        }
      });
    };

    /**
     * Adds the default parameters to the request
     * @param req
     * @returns {*}
     */
    AppWS.prototype.prepareRequest = function(req){
      if(AppConfig.isDev === true){
        req.XDEBUG_SESSION_START = 'ECLIPSE_DBGP';
      }
    };

    return AppWS;

  }]);
