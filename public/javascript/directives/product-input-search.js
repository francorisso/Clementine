angular.module('Clementine')
.directive('productInputSearch', function($q, Product){
  return {
    restrict : 'E',
    scope: {
      product : '=',
    },
    require: '^orderAddForm',
    templateUrl : 'templates/directives/product-input-search.html',
    link : function(scope, element, attrs, orderAddForm){
      scope.promise = null;
      scope.products = [];

      scope.searchText = function(searchTerm) {
        scope.products = Product.search(searchTerm)
          .then(function(response){
            return response.data;
          });
      };

      scope.selectProduct = function(product) {
        scope.product = product;
      };
    }
  };
});