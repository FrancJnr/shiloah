<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Monthly Rental Schedule</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
    include('ip_test.php');      
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
    //TODAY'S DATE
     $start =  date("F Y");
 
     //1 MONTH FROM TODAY
     $end = date("F Y",strtotime("+0 months"));
?>

<style type="text/css">
 input[type="text"]{
	border: 1px solid;
	background:#f8f8f8;
	width: 200px;
	height: 25px;
	font-size: 12pt;
	font-family:"Calibri";
}   
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {	
    $('[id^="btnSave"]').live('click', function() {
	var r = confirm("Are you sure, You want to Import Items?");
        if(r==true)
        {
            itemimport();
        }	
    });
    function itemimport()
    {
        $('#cc').html("");			
        var url="import_item.php";			
        $("#form2").ajaxSubmit({
            url:url,		
            data:$(this).serialize(),
            datatype:"json",
            beforeSubmit: function() {
                //$('#cc').html("***");				
            },
            success: function(msg) {
                $('#cc').html(msg);
            }
        });
    }
});
</script>
<!--<link href="style_progress.css" rel="stylesheet" type="text/css" /> -->
</head>

<body id="dt_example" style="width: 90%">
    <form id="myForm" name="myForm" action="" method="post">
    <br>
    <h1 align='CENTER'>SCD INVOICE</h1>
    <br><br>
    <table width='100%' class='table6'>
	<tr>
	    <th colspan='2'>Generate Scd Invoice</th>
	</tr>
	<tr>
	    <td>Browse for excel file <font color='red'>*</font></td>
	    <td id=''>
		<input type='file' name='excelfile' id='excelfile' />   
	    </td>
	</tr>	
	<tr>
	    <td colspan='2' align='right'>		
		<button type="button" id="btnSave">Save</button>
	    </td>
	</tr>
    </table>
    <span id='heading'></span>
    <div id='result'>
    
    </div>
    </br>
    </form>
    &nbsp;&nbsp;<font color=red><label id="cc"></label></font>
    <br>
</form>
</body>
</html>
