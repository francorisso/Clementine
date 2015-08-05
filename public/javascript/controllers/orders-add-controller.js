angular.module('Clementine')
.controller('OrdersAddController', function($scope, $routeParams){
  $scope.title   = 'Agregar Pedido';
  $scope.message = $routeParams.message;
  if (typeof $scope.message==='undefined') {
    $scope.message = false;
  }
});