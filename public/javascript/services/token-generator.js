angular.module('Clementine')
.factory('TokenGenerator', function TokenGeneratorFactory($http){
  return {
    get : function(status){
      return $http({
        method : 'GET',
        url    : '/api/createtoken',
        params : {}
      });
    }
  };
});
