angular.module('Clementine')
.directive('orderListRow', function(Order){
  return {
    restrict : 'E',

    scope: {
      status : '='
    },

    templateUrl : 'templates/directives/order-list-row.html',

    link : function(scope, element, attrs) {
      scope.orders = [];

      scope.$watch('status', function(){
        scope.load();
      });

      scope.showDetails = function(order)
      {
        if (order.showDetails) {
          order.showDetails = false;
        }
        else {
          order.showDetails = true;
        }
      };

      scope.showMenuItem = function(fn, status)
      {
        switch (fn) {
          case 'addItem':
          case 'setAsRequired':
            return status == 'pending';
            break;

          case 'setAsDelivered':
            return status == 'required';
            break;

          case 'setAsCanceled':
            return status !== 'canceled';
            break;

          case 'duplicate':
            return status !== 'pending';
            break;
        }
        return false;
      }

      scope.setAsRequired = function(order)
      {
        Order.changeStatus(order.id, 'required')
          .then(function(response){
            scope.load();
          });
      }

      scope.load = function()
      {
        Order.get(scope.status)
          .then(function(response){
            scope.orders = response.data;
          });
      };

      scope.load();
    }
  };
});