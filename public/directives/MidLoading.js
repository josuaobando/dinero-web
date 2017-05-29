'use strict';

angular.module('DirectiveModule')
	.directive('loading', ['$http', function($http){
		return {
			restrict: 'A',
			link: function(scope, elm, attrs){
				scope.isLoading = function(){
					return $http.pendingRequests.length > 0;
				};

				scope.$watch(scope.isLoading, function(v){
					if(v){
						elm.addClass('loading');
						elm.show();
					}else{
						elm.removeClass('loading');
						elm.hide();
					}
				});
			}
		};

	}]);