angular.module('Clementine')
.controller('OrdersIndexController', function($scope, $routeParams, $http, Order){
    $scope.title   = 'Pedido';
    $scope.status = $routeParams.status;
    $scope.statuses = [
      {
        name: 'pending',
        label: 'Pendientes'
      },
      {
        name: 'required',
        label: 'Requeridos'
      },
      {
        name: 'delivered',
        label: 'Entregados'
      },
      {
        name: 'canceled',
        label: 'Cancelados'
      }
    ];
    $scope.makeOrder = function(order) {
      $http({
        method : 'POST',
        url    : '/api/order',
        data   : {
          update_status : 1,
          order_id : order.id
        }
      })
      .success(function(){
        Order.all()
        .success(function(orders){
          $scope.orders = orders;
        });
      });
    }

    $scope.showDetails = function(order){
      if(order.showDetails) {
        order.showDetails = false;
      }
      else {
        order.showDetails = true;
      }
    }

    Order.all()
      .success(function(orders){
        $scope.orders = orders;
      });
});