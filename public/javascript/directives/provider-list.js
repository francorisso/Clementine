angular.module('Clementine')
.directive('providerList', function(Provider){
  return {
    restrict : 'E',
    scope: {
      searchTerm : '='
    },
    templateUrl : 'templates/directives/provider-list.html',
    link : function(scope, element, attrs){
      scope.search = function(){
        Provider.search(scope.searchTerm)
          .then(function(response){
            scope.providers = response.data;
          });
      };
      scope.$watch('searchTerm', function(){
        scope.search();
      });
    }
  };
});