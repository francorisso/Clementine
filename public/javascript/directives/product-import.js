angular.module('Clementine')
.directive('productImport', function(Product, TokenGenerator){
  return {
    restrict : 'E',
    scope: {
    },
    templateUrl : 'templates/directives/product-import.html',
    link : function(scope, element, attrs){
      scope.token = '';
      TokenGenerator.get().then(function(response){
        scope.token = response.data;
      });
    }
  };
});