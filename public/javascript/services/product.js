angular.module('Clementine')
.factory('Product', function ProductFactory($http){
  return {
    search : function(searchTerm){
      if (typeof searchTerm==='undefined') {
        searchTerm = null;
      }
      return $http({
        method : 'GET',
        url    : '/api/product',
        params : {
          limit : 10,
          searchTerm : searchTerm
        }
      });
    }
  };
});
