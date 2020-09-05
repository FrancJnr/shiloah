<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
	<title>LEGAL DETAILS REPORT</title>
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
<style type="text/css" media="screen">
@import "../css/print.css";
@import "../css/Site.css";
@import "../css/TableTools.css";
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$('#buildingmasid')[0].focus()
	$('#buildinglistTbl').hide();	
	$('[id^="buildingmasid"]').live('change', function() {		
		var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
		if($(this).val()!="")
		{
		    $('#buildingDiv').empty();
			var url="load_legal.php";
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){				
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#buildingDiv').append(response.divContent);
                                                $('#heading').html("<strong>"+response.heading+"<strong>");  
					}					
				});
			});
			
		}
		else
		{
			$('#buildinglistTbl').hide('slow');
		}
	});	
	$('[id^="btnPrint"]').live('click', function() {
		$('.printable').print();
	});
});
</script>
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="#" method="post">
<br>
<p><font size="+1" color="#000066"> <b> LEGAL REPORT </b> </font> </p><hr color="#000066" height="2px"></br>
Building <font color="red">*</font>
<select id="buildingmasid" name="buildingmasid" style='width: 225px;'>
	<option value="" selected>----Select Building----</option>        
	<?php loadBuilding();?>
        <option value="0"> ALL </option>
</select>
<button type="button" id="btnPrint">Print</button>
<p id='heading' align='center'></p>
<div id='buildingDiv'>
    
</div>
<font color=red><label id="cc"></label></font>
</form>
</body>
</html>
