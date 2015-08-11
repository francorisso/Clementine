angular.module('Clementine')
.config(['$routeProvider', function($route){
  $route
    .when('/orders', {
      redirectTo : '/orders/pending'
    })
    .when('/proveedores', {
      templateUrl : '/templates/pages/proveedores/index.html',
      controller  : 'ProveedoresIndexController'
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
    .when('/products/import', {
      templateUrl : '/templates/pages/products/index.html',
      controller  : 'ProductsIndexController'
    })
    .when('/',{
      redirectTo  : '/orders'
    });
}]);