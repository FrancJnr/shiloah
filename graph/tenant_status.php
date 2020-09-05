  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
    // Load the Visualization API and the piechart package.
    google.load('visualization', '1', {'packages':['corechart']});
      
    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);    
    function drawChart() {
      var jsonData = $.ajax({
          url: "get_tenant_status.php",	  
          data: {myData:"new"},          
          datatype:"json",
          async: false
          }).responseText;
          
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);
          
      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
      chart.draw(data, {title: 'Tenancy Status',  is3D: 'true', width: 800, height: 600});
    }    
    </script>
    <script type="text/javascript">
      $(document).ready(function() {
      $('#btnGet').click(function(e) {
          //e.preventDefault();
  
          var str = $("form").serialize();          
          
          //$.ajax({
          //    type : 'GET',
          //    url : 'get_tenant_status.php',
          //    dataType : 'json',
          //    data: str,
          //    success: function(data) {
          //        var data1 = data
          //        $('#chart_div').text(data1) 
          //    }
          //});
          // Load the Visualization API and the piechart package.
            google.load('visualization', '1', {'packages':['corechart']});
              
            // Set a callback to run when the Google Visualization API is loaded.
            google.setOnLoadCallback(drawChart(str));
            //alert(str);
                      
          return false;
      });
       function drawChart(x) {
              var jsonData = $.ajax({                  
                  url: "get_tenant_status.php",                  
                  data:x,          
                  datatype:"json",
                  async: false
                  }).responseText;
                  
              // Create our data table out of JSON data loaded from server.
              var data = new google.visualization.DataTable(jsonData);
                  
              // Instantiate and draw our chart, passing in some options.
              var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
              chart.draw(data, {title: 'Mega Group Tenant Status',  is3D: 'true', width: 800, height: 600});
       }
  });
    </script>
  </head>
<?PHP //date
 
    FUNCTION DateSelector($inName, $useDate=0) 
    { 
        /* create array so we can name months */ 
        $monthName = ARRAY(1=> "January", "February", "March", 
            "April", "May", "June", "July", "August", 
            "September", "October", "November", "December"); 
 
        /* if date invalid or not supplied, use current time */ 
        IF($useDate == 0) 
        { 
            $useDate = TIME(); 
        } 
 
        /* make month selector */ 
        ECHO "<SELECT NAME=" . $inName . "Month>\n"; 
        FOR($currentMonth = 1; $currentMonth <= 12; $currentMonth++) 
        { 
            ECHO "<OPTION VALUE=\""; 
            ECHO INTVAL($currentMonth); 
            ECHO "\""; 
            IF(INTVAL(DATE( "m", $useDate))==$currentMonth) 
            { 
                ECHO " SELECTED"; 
            } 
            ECHO ">" . $monthName[$currentMonth] . "\n"; 
        } 
        ECHO "</SELECT>"; 
 
        /* make day selector */ 
        //ECHO "<SELECT NAME=" . $inName . "Day>\n"; 
        //FOR($currentDay=1; $currentDay <= 31; $currentDay++) 
        //{ 
        //    ECHO "<OPTION VALUE=\"$currentDay\""; 
        //    IF(INTVAL(DATE( "d", $useDate))==$currentDay) 
        //    { 
        //        ECHO " SELECTED"; 
        //    } 
        //    ECHO ">$currentDay\n"; 
        //} 
        //ECHO "</SELECT>"; 
 
        /* make year selector */ 
        ECHO "<SELECT NAME=" . $inName . "Year>\n"; 
        $startYear = DATE( "Y", $useDate); 
        FOR($currentYear = $startYear - 5; $currentYear <= $startYear+5;$currentYear++) 
        { 
            ECHO "<OPTION VALUE=\"$currentYear\""; 
            IF(DATE( "Y", $useDate)==$currentYear) 
            { 
                ECHO " SELECTED"; 
            } 
            ECHO ">$currentYear\n"; 
        } 
        ECHO "</SELECT>"; 
 
    } 
?> 
  <body>
    <form name='form' id='form'>
      Choose a Date: <?PHP DateSelector( "t"); ?>
      &nbsp;&nbsp;&nbsp;
      <button type="submit" name='btnGet' id='btnGet' >Get Values</button>
      <input type='hidden' id='myData' name='myData' value='edit' />
      
      <!--Div that will hold the pie chart-->
      <center><div id="chart_div"></div></center>
    </form>
  </body>
</html>