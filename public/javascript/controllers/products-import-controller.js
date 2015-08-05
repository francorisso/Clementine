angular.module('Clementine')
.controller('ProductsImportController', function($scope, $routeParams, $http){
  $scope.title   = 'Productos';
  $scope.token   = null;
  $http.get('/api/createtoken')
  .success(function(data){
    $scope.token = data;
  });
});