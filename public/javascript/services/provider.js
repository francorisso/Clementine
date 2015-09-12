angular.module('Clementine')
.factory('Provider', function OrderFactory($http){
  return {
    search : function(searchTerm){
      if (typeof searchTerm==='undefined') {
        searchTerm = null;
      }
      return $http({
        method : 'GET',
        url    : '/api/provider',
        params : {
          limit : 100,
          searchTerm : searchTerm
        }
      });
    }
  };
});
