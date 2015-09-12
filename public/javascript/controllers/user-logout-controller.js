angular.module('Clementine')
.controller('UserLogoutController', function($location, User){
  User.logout()
  .then(function(response){
    $location.path('/login');
  });
});