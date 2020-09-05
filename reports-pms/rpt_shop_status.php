<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
	<title>SHOP DETAILS REPORT</title>
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
	#sortable1, #sortable2 { list-style-type: none; margin: 0; padding: 0; zoom: 1; }
	#sortable1 li, #sortable2 li { margin: 0 5px 5px 5px; padding: 3px; width: 99%; }
        ul
        {
            list-style-type:none;
            padding:0px;
            margin:0px;
        }
        ul li
        {
            background-image:url(sqpurple.gif);
            background-repeat:no-repeat;
            background-position:0px 5px; 
            padding-left:14px;
        }
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$('#buildingmasid')[0].focus()	
	$('[id^="buildingmasid"]').live('change', function() {
		$('#cc').html("");
		var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
		if($(this).val()!="")
		{
			$('#buildingDiv').empty();
			var url="load_shop_status.php?item=shopstatus&buildingmasid ="+$(this).val();
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){				
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#buildingDiv').append(response.divContent);
                                        }
				});
			});
		}		
	});
	$('[id^="btnPrint"]').live('click', function() {
		$('.printable').print();
	});
	$("#btnExport").click(function(e) {
		window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#buildingDiv').html()));
		e.preventDefault();
	});
        
        $('[id^="btnDetails"]').live('click', function() {            
            var tenantmasid = $(this).val();
            if(tenantmasid !="")
            {
                window.open("load_shop_offerletter.php?tenantmasid="+tenantmasid, "Print PDF", "width=800,height=800,toolbar:false,");
                return false;            
            }
        });
});
</script>
<style>
.intro
{
	font-size:150%;
	color:green;
}
</style>
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="#" method="post">
<p><font size="+1" color="#000066"> <b> SHOP STATUS </b> </font> </p><hr color="#000066" height="2px"></br>
Building <font color="red">*</font>
<select id="buildingmasid" name="buildingmasid" style='width: 225px;'>
	<!--<option value=""> ALL </option>-->
	<option value=""> Select </option>
	<?php loadBuilding();?>	
</select>
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
