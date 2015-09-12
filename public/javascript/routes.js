angular.module('Clementine')
.config(['$routeProvider', function($route){
  $route
    .when('/login', {
      templateUrl : '/templates/pages/users/login.html',
      controller  : 'UserLoginController'
    })
    .when('/logout', {
      template    : ' ',
      controller  : 'UserLogoutController'
    })
    .when('/orders', {
      redirectTo : '/orders/pending'
    })
    .when('/providers', {
      templateUrl : '/templates/pages/providers/index.html',
      controller  : 'ProvidersIndexController'
    })
    .when('/orders/create',{
      templateUrl : '/templates/pages/orders/add.html',
      controller  : 'OrdersAddController'
    })
    .when('/orders/:status',{
      templateUrl : '/templates/pages/orders/index.html',
      controller  : 'OrdersIndexController'
    })
    .when('/products', {
      templateUrl : '/templates/pages/products/index.html',
      controller  : 'ProductsIndexController'
    })
    .when('/stats', {
      templateUrl : '/templates/pages/stats/index.html',
      controller  : 'StatsIndexController'
    })
    .when('/products/import', {
      templateUrl : '/templates/pages/products/index.html',
      controller  : 'ProductsIndexController'
    })
    .when('/',{
      redirectTo  : '/orders'
    });
}]);