angular.module('Clementine')
.controller('UserLoginController', function(
  $scope,
  $routeParams,
  $location,
  $mdDialog,
  User
){
  $scope.title   = 'Login';
  $scope.message = $routeParams.message;
  if (typeof $scope.message==='undefined') {
    $scope.message = false;
  }
  $scope.user  = {};
  $scope.login = function() {
    User.login($scope.user)
    .then(function(res){
      $location.path('/');
    },
    function(res){
      var alertDialog = $mdDialog.alert()
        .clickOutsideToClose(true)
        .title('Login incorrecto')
        .content('Chequea tus datos e intentá de nuevo.')
        .ok('Ok');
      $mdDialog.show(alertDialog);
    });
  };

});