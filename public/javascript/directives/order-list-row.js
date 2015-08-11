angular.module('Clementine')
.directive('orderListRow', function($filter, Product, Order){
  return {
    restrict : 'E',
    scope: {
      status : '='
    },
    templateUrl : 'templates/directives/order-list-row.html',
    link : function(scope, element, attrs){
      scope.orders = 0;

      scope.showDetails = function(order){
        if (order.showDetails) {
          order.showDetails = false;
        }
        else {
          order.showDetails = true;
        }
      }

      scope.load = function() {
        Order.get(scope.status)
          .then(function(orders){
            scope.orders = orders;
          });
        }
      };
  };
});