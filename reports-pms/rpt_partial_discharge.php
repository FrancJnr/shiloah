<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
	<title>PARTIAL DISCHARGE</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
    function loadBuilding()
    {
         $companymasid = $_SESSION['mycompanymasid'];
		$sql = "select buildingmasid, buildingname from mas_building WHERE companymasid =".$companymasid." order by buildingname asc";
        $result = mysql_query($sql);
        if($result != null)
        {
            while($row = mysql_fetch_assoc($result))
            {
                echo("<option value=".$row['buildingmasid'].">".$row['buildingname']."</option>");		
            }
        }
    }
?>
<style>
    @import "../css/print.css";	
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    var url="load_partial_discharge.php?item=partial";	    
    var dataToBeSent = $("form").serialize();	    
    $.getJSON(url,dataToBeSent, function(data){
        $.each(data.error, function(i,response){
            if(response.s =="Success")
            {
                $("#cc").html(response.msg);                                        
            }
            else
            {					
                $("#cc").html(response.msg);
            }				
        });
    });
});
</script>
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="#" method="post">
<p><font size="+1" color="#000066"> <b> PARTIAL DISCHARGE REPORT </b> </font> </p><hr color="#000066" height="2px"></br>

<button type="button" id="btnPrint">Print</button>
<button type="button" id="btnExport">Export</button>
<div id='buildingDiv'>

</div>
</form>
&nbsp;&nbsp;<font color=red><label id="cc"></label></font>
<br>
&nbsp;&nbsp;<font color=red><label id="st"></label></font>
</body>
</html>
