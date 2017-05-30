'use strict';

angular.module('InterfaceManagerModule', ['ngAnimate'])

	.factory('InterfaceManager', ['$location', '$state', 'NotificationManager', 'AppSession', function($location, $state, NotificationManager, AppSession){

		var InterfaceManager = {};

		/**
		 * Get the Session Id
		 *
		 * @returns string
		 */
		InterfaceManager.getSid = function(){
			var sid = null;
			var pageSid = angular.element('[id="sid"]').val();
			var appSid = AppSession.get('sid');

			if(!appSid){
				sid = pageSid;
				AppSession.set('sid', pageSid);
			}else{
				if(pageSid && pageSid != appSid){
					AppSession.clear();
					AppSession.set('sid', pageSid);
					sid = pageSid;
				}else{
					sid = appSid;
				}
			}

			return sid;
		};

		/**
		 * add filter to environment
		 *
		 * @param type
		 * @param filterData
		 */
		InterfaceManager.addFilter = function(type, filterData){
			AppSession.set(type, filterData);
		};

		/**
		 * get filter from environment
		 *
		 * @param type
		 * @param callback
		 */
		InterfaceManager.getFilter = function(type, callback){
			if (!(callback instanceof Function)){
				callback = function(){}
			}

			var filterData = AppSession.get(type);

			callback(filterData);
		};

		/**
		 * Configuration by pop up notification
		 * @array {{ttl: number, enableHtml: boolean}}
		 */
		InterfaceManager.configMsg = {ttl: 5000, enableHtml: false};

		/**
		 * Switches to a different state in the system.
		 * @param state
		 * @param UIParams
		 */
		InterfaceManager.changeLocation = function(state, UIParams){
			$state.go(state, UIParams);
		};

		/**
		 * Handles success messages to the interface
		 * @param successfulMessage
		 */
		InterfaceManager.handleSuccessfulMessage = function(successfulMessage){
			NotificationManager.addSuccessMessage(successfulMessage, this.configMsg)
		};

		/**
		 * Handles warning messages to the interface
		 * @param warningMessage
		 */
		InterfaceManager.handleWarning = function(warningMessage){
			NotificationManager.addWarnMessage(warningMessage, this.configMsg)
		};

		/**
		 * Handles warning messages to the interface
		 * @param infoMessage
		 */
		InterfaceManager.handleInfoMessage = function(infoMessage){
			NotificationManager.addInfoMessage(infoMessage, this.configMsg)
		};

		/**
		 * Handles warning messages to the interface
		 * @param systemError
		 */
		InterfaceManager.handleSystemError = function(systemError){
			NotificationManager.addErrorMessage(systemError, {ttl: -1, enableHtml: false})
		};

		/**
		 * JQuery to Scroll Page
		 */
		jQuery(document).ready(function(){
			var offset = 220;
			var duration = 500;
			jQuery(window).scroll(function(){
				if(jQuery(this).scrollTop() > offset){
					jQuery('.back-to-top').fadeIn(duration);
				}else{
					jQuery('.back-to-top').fadeOut(duration);
				}
			});

			jQuery('.back-to-top').click(function(event){
				event.preventDefault();
				jQuery('html, body').animate({scrollTop: 0}, duration);
				return false;
			})
		});

		return InterfaceManager;
	}]);