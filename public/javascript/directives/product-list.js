angular.module('Clementine')
.directive('productList', function(Product){
  return {
    restrict : 'E',
    scope: {
    },
    templateUrl : 'templates/directives/product-list.html',
    link : function(scope, element, attrs){
      Product.all()
        .then(function(response){
          scope.products = response.data;
        });
    }
  };
});