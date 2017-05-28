'use strict';

angular.module('InterfaceManagerModule', ['ngAnimate'])

  .factory('InterfaceManager', ['$location', '$state', 'NotificationManager', function($location, $state, NotificationManager){

    var InterfaceManager = {};

    InterfaceManager.companyListData = [{companyId: 2, companyName: 'BookMaker', bySoftwareProviderCompanyId: 0}, {companyId: 3, companyName: 'BetDSI', bySoftwareProviderCompanyId: 0}, {companyId: 13, companyName: 'BetCris', bySoftwareProviderCompanyId: 0}, {companyId: 71, companyName: 'JustBet', bySoftwareProviderCompanyId: 1}];

     /**
     * Configuration by pop up notification
     * @array {{ttl: number, enableHtml: boolean}}
     */
    InterfaceManager.configMsg = {ttl : 5000, enableHtml: false};

    /**
     * Switches to a different state in the system.
     * @param state
     * @param UIParams
     */
    InterfaceManager.changeLocation = function(state, UIParams){
      $state.go(state, UIParams);
    }

    /**
     * Handles success messages to the interface
     * @param successfulMessage
     */
    InterfaceManager.handleSuccessfulMessage = function(message){
      NotificationManager.addSuccessMessage(message, this.configMsg)
    };

    /**
     * Handles warning messages to the interface
     * @param warningMessage
     */
    InterfaceManager.handleWarning = function(message){
      NotificationManager.addWarnMessage(message, this.configMsg)
    };

    /**
     * Handles warning messages to the interface
     * @param infoMessage
     */
    InterfaceManager.handleInfoMessage = function(message){
      NotificationManager.addInfoMessage(message, this.configMsg)
    };

    /**
     * Handles warning messages to the interface
     * @param message
     */
    InterfaceManager.handleSystemError = function(message){
      NotificationManager.addErrorMessage(message, {ttl : -1, enableHtml: false})
    };

    /**
     * JQuery to Scroll Page
     */
    jQuery(document).ready(function() {
      var offset = 220;
      var duration = 500;
      jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > offset) {
          jQuery('.back-to-top').fadeIn(duration);
        } else {
          jQuery('.back-to-top').fadeOut(duration);
        }
      });

      jQuery('.back-to-top').click(function(event) {
        event.preventDefault();
        jQuery('html, body').animate({scrollTop: 0}, duration);
        return false;
      })
    });

    return InterfaceManager;
  }]);