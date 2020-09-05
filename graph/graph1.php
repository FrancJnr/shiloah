<html>
  <head>
    <script type="text/javascript" src="jsapi.js"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Tenants per Month'],
          ['Mega Plaza',     11],
          ['Mega City',      7],
          ['Mega Mall',  2],
          ['Reliance Centre', 2],
          ['Katangi',    1]
        ]);

        var options = {
          title: 'Tenant Occupation per month',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart_3d" style="width: 500px; height: 400px;"></div>
  </body>
</html>