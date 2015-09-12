angular.module('Clementine')
.directive('chartProvider', function($timeout, Stats){
  return {
    restrict : 'E',
    scope: {
      title     : '@',
      graphType : '@',
      metric    : '@'
    },
    templateUrl : 'templates/directives/chart.html',
    link : function(scope, element, attrs){
      scope.chart = null;
      scope.graphOpts = {};
      scope.chartName = 'provider-'+ scope.metric + '-' + scope.graphType;
      scope.drawSpace = 'chart-' + scope.chartName;

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
        scope.graphOpts = {
          'metric'      : scope.metric,
          'graphType'   : scope.graphType,
          'title'       : scope.title
        };
        Stats.providers(scope.graphOpts)
          .then(function(response){
            //TODO: change this according to resolution
            var width = 760;
            var height = 480;
            var options = {
              width  : width,
              height : height,
              hAxis: {
                title : response.data.hAxisTitle
              },
              vAxis: {
                title : response.data.vAxisTitle
              },
              curveType : 'function'
            };
            var rows = [];
            response.data.columns.forEach(function(column){
              data.addColumn(column.dataType, column.name);
            });
            response.data.rows.forEach(function(row){
              rows.push(row);
            });
            data.addRows(rows);
            scope.chart = new google.visualization.LineChart(document.getElementById(scope.drawSpace));
            scope.chart.draw(data, options);
          });
      };
    }
  };
});