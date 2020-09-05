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
	$('#buildinglistTbl').hide();
	//buildingdetails();
	$('[id^="buildingmasid"]').live('change', function() {
		$('#cc').html("");
		var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
		if($(this).val()!="")
		{
			$('#buildingDiv').empty();
			var url="save_shop_details.php?item=shopdetails&buildingmasid ="+$(this).val();
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){				
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#buildingDiv').append(response.divContent);
						var tot_sqrft=0;var tot_shops=0;
						// vacant table
							var $c=0;
							$(".vacant").each(function() {
							    $c++;			    
							});
							tot_shops +=$c;
							var sum = 0;	
							for($i=1;$i<=$c;$i++)
							{										
								var c = 'vacant'+$i;
								$('#'+c).html("<b>"+$i+"</b>");
								var s = 'size_vacant'+$i;
								var $txt = $('#'+s).text();
								var value = removecomma($txt);				
								if(!isNaN(value) && value.length != 0) {
								    sum += parseFloat(value);								    
								}								    
							}
							tot_sqrft +=sum;
							sum = commafy(sum);							
							$('#tot_vacant').html(sum);
							$('#tot_vacant_g').html(sum);
							//$('#'+c).html("<b>"+$i+"</b>");
						// Expired Lease table
							var $c=0
							$(".exp").each(function() {
							    $c++;			    
							});
							tot_shops +=$c;
							var sum = 0;	
							for($i=1;$i<=$c;$i++)
							{							    
							    var c = 'exp'+$i;
							    //$('#'+c).html("<b>"+$i+"</b>");
							    var s = 'size_exp'+$i;
								var $txt = $('#'+s).text();
								var value = removecomma($txt);				
								if(!isNaN(value) && value.length != 0) {
								    sum += parseFloat(value);								    
								}
							}
							tot_sqrft +=sum;
							sum = commafy(sum);							
							$('#tot_exp').html(sum);
							$('#tot_exp_g').html(sum);
						// waiting list table
							var $c=0
							$(".waiting").each(function() {
							    $c++;			    
							});
							tot_shops +=$c;
							var sum = 0;			    
							for($i=1;$i<=$c;$i++)
							{
							    
								var c = 'waiting'+$i;
								//$('#'+c).html("<b>"+$i+"</b>");
								var s = 'size_waiting'+$i;
								var $txt = $('#'+s).text();
								var value = removecomma($txt);				
								if(!isNaN(value) && value.length != 0) {
								    sum += parseFloat(value);								    
								}
							}
							tot_sqrft +=sum;
							sum = commafy(sum);							
							$('#tot_waiting').html(sum);
							$('#tot_waiting_g').html(sum);
						// regular list table
							var $c=0
							$(".reg").each(function() {
							    $c++;			    
							});
							tot_shops +=$c;
							var sum = 0;			    
							for($i=1;$i<=$c;$i++)
							{							    
								var c = 'reg'+$i;
								//$('#'+c).html("<b>"+$i+"</b>");
								var s = 'size_reg'+$i;
								var $txt = $('#'+s).text();
								var value = removecomma($txt);				
								if(!isNaN(value) && value.length != 0) {
								    sum += parseFloat(value);								    
								}
							}
							tot_sqrft +=sum;
							sum = commafy(sum);							
							$('#tot_reg').html(sum);
							$('#tot_reg_g').html(sum);
							
							$('#tot_shops').html(tot_shops);							
							$('#tot_sqrft').html(commafy(tot_sqrft));							
							//$('#rt_sqrft').html(commafy(tot_sqrft));
						//$('#cc').html($c);
					}
					$('#buildinglistTbl').show('slow');
					$('input:checkbox').attr('checked', true);
				});
			});
			
		}
		else
		{
			$('#buildinglistTbl').hide('slow');
			buildingdetails();
			
		}
	});
	function buildingdetails()
	{
		$('#buildingDiv').empty();
		var url="save_shop_details.php?item='buildingdetails'";
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
	$('[id^="select_all"]').live('change', function() {
		var checkboxes = $(this).closest('form').find(':checkbox');
		if($(this).is(':checked')) {
		    checkboxes.attr('checked', 'checked');
		} else {
		    checkboxes.removeAttr('checked');
		}
	});
	$(':checkbox').live('change', function() {
		//alert($("input:checkbox:checked").length);
		var txt="";
		var $n=0;
		$(".colhead").each(function() {
			$n++;			    
		});		
		for($j=1;$j<=$n;$j++)
		{			
			var value ="";										
			var sum = 0;
			var tot = 'tot'+$j;		
			$('#'+tot).html("<b>"+commafy(sum)+"</b>");			
			$(':checkbox').each(function() {
				$(this).closest("tr").removeClass("intro");		
				if($(this).is(':checked')) {															
					var id = $(this).attr('id')					
					if(id != "select_all")
					{						
						var c = 'colvalue'+id+$j;				
						$('.'+c).each(function() {				
						    var value = removecomma($(this).text());
						    //txt += removecomma($(this).text())+" + ";						    
						    //$(this).closest("tr").addClass("intro");
						    if(!isNaN(value) && value.length != 0) {
							sum += parseFloat(value);
						    }				    				    
						});
						var tot = 'tot'+$j;		
						$('#'+tot).html("<b>"+commafy(sum)+"</b>");					
					}					
				}				
			});
			
			
		}				
		//$('#cc').html(txt);		
	}); 
	$('[id^="btnPrint"]').live('click', function() {
		$('.printable').print();
	});
	$("#btnExport").click(function(e) {
		window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#buildingDiv').html()));
		e.preventDefault();
	});
	function removecomma(val)
	{
		return String(val).replace(/\,/g, '');			    
	}
	function commafy(nStr) {
		nStr += '';
		    var x = nStr.split('.');
		    var x1 = x[0];
		    var x2 = x.length > 1 ? '.' + x[1] : '';
		    var rgx = /(\d+)(\d{3})/;
		    while (rgx.test(x1)) {
			    x1 = x1.replace(rgx, '$1' + ',' + '$2');
		    }
		    return x1 + x2;
	}


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
<p><font size="+1" color="#000066"> <b> TENANCY REPORT </b> </font> </p><hr color="#000066" height="2px"></br>
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
