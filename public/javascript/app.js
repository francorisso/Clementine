angular.module('Clementine',[
  'ngRoute',
  'ngCookies',
  'ngMaterial',
  'ngSanitize',
  'ngMdIcons'
])
.controller('AppCtrl',function($scope, $mdUtil, $mdSidenav, $location){
  $scope.toggleLeft = buildToggler('left');
  /**
   * Build handler to open/close a SideNav; when animation finishes
   * report completion in console
   */
  function buildToggler(navID) {
    var debounceFn =  $mdUtil.debounce(function(){
          $mdSidenav(navID)
            .toggle()
            .then(function () {
            });
        },300);
    return debounceFn;
  };

  $scope.closeNavigation = function(nav){
    $mdSidenav(nav).close()
      .then(function () {
      });
  };

  $scope.navigateTo = function(url){
    $location.path(url);
    $scope.closeNavigation('left');
  };
});