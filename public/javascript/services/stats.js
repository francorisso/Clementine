angular.module('Clementine')
.factory('Stats', function StatsFactory($http){
  return {
    providers : function(parameters){
      parameters['is_stat'] = true;
      return $http({
        method : 'GET',
        url    : '/api/provider',
        params : parameters
      });
    },
    products : function(parameters){
      parameters['is_stat'] = true;
      return $http({
        method : 'GET',
        url    : '/api/product',
        params : parameters
      });
    }
  };
});
