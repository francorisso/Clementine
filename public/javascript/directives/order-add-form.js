angular.module('Clementine')
.directive('orderAddForm', function($filter, Product, Order){
  return {
    restrict : 'E',
    scope: {
      order : '='
    },
    templateUrl : 'templates/directives/order-add-form.html',
    controller: function($scope, $mdToast, $animate){
      $scope.order = {
        quantity    : 1,
        order_id    : 0,
        price       : 0,
        product     : {},
        provider    : {}
      };

      $scope.alertMessage = function(message) {
        $mdToast.show(
          $mdToast.simple()
            .content(message)
            .position('bottom right')
            .hideDelay(3000)
        );
      };
    },
    link : function(scope, element, attrs){
      scope.timer = 0;
      scope.totalcost = 0;

      scope.$watchGroup(['order.provider','order.quantity'], function(){
        var amount = scope.order.quantity * scope.order.provider.price;
        scope.totalcost = $filter('currency')(amount, '$', 2);
      });

      scope.makeOrder = function(order){
        Order.save(order.provider.id)
          .success(function(orderResponse){
            order.id = orderResponse.id;
            Order.saveItem(order)
              .success(function(result){
                scope.alertMessage('Item agregado');
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