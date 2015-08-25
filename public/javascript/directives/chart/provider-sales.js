angular.module('Clementine')
.directive('providerSales', function($timeout, Stats){
  return {
    restrict : 'E',
    scope: {
    },
    templateUrl : 'templates/directives/chart.html',
    link : function(scope, element, attrs){
      scope.chart = null;
      scope.metric = null;
      scope.graphOpts = {};

      google.load('visualization', '1', {
        packages : ['corechart'],
        callback : function() {
          scope.$watchGroup(['stat','metric'], function(){
            scope.drawChart();
          });
        }
      });

      scope.drawChart = function(){
        // Create the data table.
        var data = new google.visualization.DataTable();
        switch (scope.stat) {
          case 'providers':
            var options = {
              title  : 'Proveedores Top',
              width  : 900,
              height : 400,
              hAxis: {
                title : 'Fecha'
              },
              vAxis: {
                title : 'Proveedor'
              },
              curveType : 'function'
            };
            scope.graphOpts = {
              'type'   : 'top',
              'metric' : scope.metric
            };
            data.addColumn('string', 'Fecha');
            Stats.providers(scope.graphOpts)
              .then(function(response){
                var rows = [];
                response.data.providers.forEach(function(provider){
                  data.addColumn('number', provider.name);
                });
                response.data.rows.forEach(function(row){
                  rows.push(row);
                });
                data.addRows(rows);
                scope.chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                scope.chart.draw(data, options);
              });

            break;
        }
      };


    }
  };
});