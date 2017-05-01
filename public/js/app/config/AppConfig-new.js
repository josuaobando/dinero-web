'use strict';

angular.module('AppModule', [])

	.provider('AppConfigProvider', function(){
		var self = this;
		this.config = {
			ws_timeout: 120000
		};
		this.$get = function(){
			return {config: self.config};
		};
	})

	.constant('AppConfig', {
		isDev: false,
		ws: '/ws/controller.php'
	});