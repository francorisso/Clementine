angular.module('Clementine')
.directive('productList', function(Product){
  return {
    restrict : 'E',
    scope: {
      searchTerm : '='
    },
    templateUrl : 'templates/directives/product-list.html',
    link : function(scope, element, attrs){
      scope.search = function(){
        Product.search(scope.searchTerm)
          .then(function(response){
            scope.products = response.data;
          });
      };
      scope.$watch('searchTerm', function(){
        scope.search();
      });
      scope.search();
    }
  };
});