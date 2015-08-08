angular.module('Clementine')
.directive('productImport', function(Product){
  return {
    restrict : 'E',
    scope: {
    },
    templateUrl : 'templates/directives/product-import.html',
    link : function(scope, element, attrs){
    }
  };
});