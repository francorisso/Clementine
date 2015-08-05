angular.module('Clementine')
.controller('ProductsIndexController', function($scope, $routeParams, $sce, Product){

    $scope.title   = 'Productos';
    $scope.added   = $routeParams.added;
    $scope.updated = $routeParams.updated;
    $scope.message = false;
    if (
      typeof $scope.added !== 'undefined' &&
      typeof $scope.updated !== 'undefined'
    ) {
      $scope.message = $sce.trustAsHtml([
        'Importado exitosamente<br /><br />',
        'Productos agregados: ' + $scope.added + '<br />',
        'Productos actualizados: ' + $scope.updated
      ].join('\n'));
    }
    $scope.products = [];
    Product.all()
      .success(function(data){
        $scope.products = data;
      });

    $scope.timer = 0;
    $('input[name="product-name"]').on('keyup', function(e){
      var productName = $(this).val();
      clearTimeout($scope.timer);
      $scope.timer = setTimeout(function(){
        $scope.products = Product.search(productName)
          .success(function(data){
            if (data.length>0) {
              $scope.products = data;
            }
            else {
              $scope.products = [];
            }
          });
      }, 200);
    });
  });