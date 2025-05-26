$(function () {

  'use strict';

  /* ChartJS
   * -------
   * Here we will create a few charts using ChartJS
   */

  // -----------------------
  // - MONTHLY SALES CHART -
  // -----------------------

  // Get context with jQuery - using jQuery's .get() method.
  var canvas = $('#salesChart').get(0);
  if (canvas) {
    var salesChartCanvas = canvas.getContext('2d');
    var salesChart = new Chart(salesChartCanvas);

    var salesChartData = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label               : 'Electronics',
          fillColor           : 'rgb(210, 214, 222)',
          strokeColor         : 'rgb(210, 214, 222)',
          pointColor          : 'rgb(210, 214, 222)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgb(220,220,220)',
          data                : [65, 59, 80, 81, 56, 55, 40]
        }
      ]
    };

    // Aquí puedes continuar con la configuración del gráfico...
    // salesChart.Line(salesChartData, salesChartOptions);
  }

});
