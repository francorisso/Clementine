angular.module('Clementine')
.directive('userTopInfo', function($location, Provider){
  return {
    restrict : 'E',
    scope: {
      user : '='
    },
    templateUrl : 'templates/directives/user-top-info.html',
    link : function(scope, element, attrs){
      scope.logout = function(){
        $location.path('/logout');
      }
    }
  };
});