angular.module('Clementine')
.directive('productInputSearch', function(Product){
  return {
    restrict : 'E',
    scope: {
      product : '=',
    },
    require: '^orderAddForm',
    templateUrl : 'templates/directives/product-input-search.html',
    link : function(scope, element, attrs, orderAddForm){
      scope.showAutocomplete = false;
      scope.products     = [];
      scope.timer        = 0;
      element.find('input[name="product-name"]').on('keyup', function(e){
        clearTimeout(scope.timer);
        scope.timer = setTimeout(function(){
          scope.products = Product.search(scope.product.name)
            .success(function(data){
              if (data.length>0) {
                scope.showAutocomplete = true;
                scope.products = data;
              }
              else {
                scope.products = [];
                scope.hideAutocomplete();
              }
              scope.timerAutocomplete = setTimeout(scope.hideAutocomplete, 3000);
            });
        }, 500);
      });

      scope.hideAutocomplete = function(){
        scope.showAutocomplete = false;
      };

      scope.selectProduct = function(product) {
        scope.product   = product;
        scope.showAutocomplete = false;
      };
    }
  };
});