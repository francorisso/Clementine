angular.module('Clementine')
.factory('authInterceptor', ['$q', '$location', function($q, $location) {
  return {
    responseError: function(response){
      if (response.status === 401) {
        $location.path('/login');
        return $q.reject(response);
      }
      if (response.status === 404) {
        $location.path('/not-found');
        return $q.reject(response);
      }
      else {
        return $q.reject(response);
      }
    },

    request: function(config)
    {
      if (config.method==='POST') {
        if ('data' in config) {
          config.data['_token'] = __token;
        }
        else {
          config['data'] = {
            '_token' : __token
          };
        }
      }
      return config;
    }
  }
}])
.config(['$httpProvider',function($httpProvider) {
  $httpProvider.interceptors.push('authInterceptor');
}]);