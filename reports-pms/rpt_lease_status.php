<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
	<title>LEASE STATUS</title>
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
    $('#buildingmasid')[0].focus()	
    $('[id^="btnView"]').live('click', function() {
        $('#cc').html("");
        $('#divContent').empty();
        $buildingmasid = $('#buildingmasid').val();
        var url="load_lease_status.php?item=leasestatus&buildingmasid ="+$buildingmasid;        
        var dataToBeSent = $("form").serialize();
        $.getJSON(url,dataToBeSent, function(data){				
            $.each(data.error, function(i,response){                
                if(response.s == "Success")
                {
                    $('#divContent').html(response.divContent);					
                }				
            });
        });
    });        
    $('[id^="btnPrint"]').live('click', function() {
        $('.printable').print();
    });
    $("#btnExport").click(function(e) {
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#divContent').html()));
        e.preventDefault();
    });	
});
</script>
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="#" method="post">
&nbsp;&nbsp;<font color=red><label id="cc"></label></font>
</br>
<p><font size="+1" color="#000066"> <b> LEASE STATUS </b> </font> </p><hr color="#000066" height="2px"></br>
Building <font color="red">*</font>
<select id="buildingmasid" name="buildingmasid" style='width: 225px;'>
	<!--<option value=""> ALL </option>-->
	<option value=""> Select </option>
	<?php loadBuilding();?>	
</select>
<button type="button" id="btnView">View</button>
<button type="button" id="btnPrint">Print</button>
<button type="button" id="btnExport">Export</button>
<div id='divContent'>

</div>
</form>
</body>
</html>
