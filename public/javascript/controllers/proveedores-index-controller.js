angular.module('Clementine')
.controller('ProveedoresIndexController', function($scope, $routeParams, $http, Pedido){
    $scope.title   = 'Proveedores';
    $http({
      method : 'GET',
      url    : '/api/proveedor'
    })
    .success(function(proveedores){
      $scope.proveedores = proveedores;
    });
});