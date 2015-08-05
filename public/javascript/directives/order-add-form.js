angular.module('Clementine')
.directive('orderAddForm', function(Product, Order){
  return {
    restrict : 'E',
    scope: {
      order : '=',
      message : '='
    },
    templateUrl : 'templates/directives/order-add-form.html',
    controller: function($scope){
      $scope.order = {
        quantity    : 1,
        order_id    : 0,
        price       : 0,
        product     : {},
        provider    : {}
      };
    },
    link : function(scope, element, attrs){
      scope.timer = 0;
      scope.priceTotal = function(){
        if (scope.order.provider && typeof scope.order.provider.price==='undefined') {
          return 0;
        }
        return scope.order.quantity * scope.order.provider.price;
      };

      scope.makeOrder = function(order){
        Order.save(order.provider.id)
          .success(function(orderResponse){
            order.id = orderResponse.id;
            Order.saveItem(order)
              .success(function(result){
                scope.message = 'Item agregado';
                scope.order = {
                  quantity    : 1,
                  order_id    : 0,
                  price       : 0,
                  product     : {},
                  provider    : {}
                };
              });
          });
      };
    }
  };
});