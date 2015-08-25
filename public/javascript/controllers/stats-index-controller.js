angular.module('Clementine')
.controller('StatsIndexController', function($scope, $routeParams){
  $scope.title   = 'Estadisticas';
  $scope.message = $routeParams.message;
  if (typeof $scope.message==='undefined') {
    $scope.message = false;
  }
});