angular.module('Clementine')
.controller('OrdersIndexController', function($scope, $routeParams, $http, Order){
    $scope.title    = 'Pedidos';
    $scope.status   = $routeParams.status;
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
});