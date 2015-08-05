angular.module('Clementine')
.factory('Product', function ProductFactory($http){
  return {
    search : function(searchTerm){
      return $http({
        method : 'GET',
        url    : '/api/product',
        params : {
          limit : 10,
          searchTerm : searchTerm
        }
      });
    },
    all : function(){
      return $http({
        method : 'GET',
        url    : '/api/product'
      });
    }
  };
});
