'use strict';

angular.module('ConfigModule', [])

	.provider('Config', function(){
		var self = this;
		this.config = {
			ws_timeout: 60000
		};
		this.$get = function(){
			return {config: self.config};
		};
	})

	.constant('ClientConfig', {

		dev: false,
		wsController: '',
		auth: ''

	});