'use strict';

angular.module('ConfigModule', [])

  .provider('Config', function () {
    var self = this;
    this.config = {
      ws_timeout: 120000
    };
    this.$get = function () {
      return { config: self.config };
    };
  })

  .constant('ClientConfig', {

    dev: false,
    wsController: 'http://cashier.bonus.localhost:8080/ws/wsBonus.php',
    sys_access_pass: '1'

  });