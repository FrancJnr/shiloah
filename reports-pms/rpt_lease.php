<?php
	require('../fpdf_print/fpdf.php');
	session_start();
	//$pdf = new FPDF();
	//$pdf->AddPage();
	//$pdf->SetFont('Arial','B',16);
	//$pdf->Cell(40,10,'Hello World!');
	//$pdf->Output();	
	if (! isset($_SESSION['myusername']) ){
		header("location:../index.php");
	}
	include('../config.php');
	include('../MasterRef_Folder.php');
	if($_SERVER['REQUEST_METHOD'] == "POST")  
	{
		//print('<pre>');
		//print_r($_POST);
		//print('</pre>');
		
		$post_tenantmasid = $_POST['grouptenantmasid'];
		$txt = "'".mysql_real_escape_string($_POST['txt'])."'";
		$update ="update rpt_lease set rowcontent = $txt where grouptenantmasid =$post_tenantmasid";
		mysql_query($update);
		//echo $update;
		echo "Data Updated Successfully"; 
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Lease</title>
<style>
	#sortable1, #sortable2 { list-style-type: none; margin: 0; padding: 0; zoom: 1; }
	#sortable1 li, #sortable2 li { margin: 0 5px 5px 5px; padding: 3px; width: 99%; }
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	 var options = {
            width: 1200,
            height: 300,
//            controls: "bold italic underline strikethrough subscript superscript | font size " +
//                    "style | color highlight removeformat | bullets numbering | outdent " +
//                    "indent | alignleft center alignright justify | undo redo | " +
//                    "rule link image unlink | cut copy paste pastetext | print source"
	controls: "bold italic underline strikethrough subscript superscript | font size " +
                    "style | color highlight removeformat | bullets numbering | outdent " +
                    "indent | alignleft center alignright justify | undo redo | " +
                    "rule link unlink | cut copy paste pastetext "
        };
	loadTenantOfferletter("loadofferletterforlease");	
	
	function loadTenantOfferletter(itemtype)
	{
		var url="load_report.php?item="+itemtype;	
		$.getJSON(url,function(data){
		    $.each(data.error, function(i,response){
			if(response.s == "Success")
			{
			    $('#grouptenantmasid').empty();
			    $('#grouptenantmasid').append( new Option("-----Select Group Tenant-----","",true,false) );
			    $.each(data.myResult, function(i,response){
				    var a = response.leasename+" ("+response.shopcode+")";
				    $('#grouptenantmasid').append( new Option(a,response.grouptenantmasid,true,false) );
			    });
			}
			else
			{
			    $('#grouptenantmasid').empty();
			    $('#grouptenantmasid').append( new Option("-----Select Group Tenant-----","",true,false) );
			    alert(response.s);
			}
		    });		
		});
	}
	$('#btnView').click(function(){
	if($("#grouptenantmasid option:selected").val()== "")
	{
		alert("Please select tenant");return false;
	}
		var url="save_rpt_lease.php";
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
	 $('.example4demo').click(function () {
                    window.open("save_print_lease.php?grouptenantmasid=555", "Print PDF", "width=800,height=800,toolbar:false,");
                    return false;
	});
	$('[id^="btnUpdate"]').live('click', function() {
		var n = $(this).attr("name");
		var a = $("#editor"+n).val();
		$("#content"+n).html(a);
		$("#hidcontent"+n).val(a);
		$("#divText"+n).css("height","0 px");
		$("#divText"+n).css("visibility","hidden");
		$("#divText"+n).css("display","none");
		$("#btnEdit"+n).text("Edit");
        });
	$('[id^="btnEdit"]').live('click', function() {
		var n = $(this).attr("name");
		if($("#btnEdit"+n).text() =="Edit")
		{
			var a = $("#content"+n).html();
			var editor = $("#editor"+n).html(a).cleditor(options)[0];
			editor.updateFrame();
			$("#divText"+n).css("height","");
			$("#divText"+n).css("visibility","");
			$("#divText"+n).css("display","");
			$("#btnEdit"+n).text("Cancel");
		}
		else if($("#btnEdit"+n).text() =="Cancel")
		{
			$("#divText"+n).css("height","0 px");
			$("#divText"+n).css("visibility","hidden");
			$("#divText"+n).css("display","none");
			$("#btnEdit"+n).text("Edit");
		}
		
	});
	
	$('[id^="chkBox"]').live('click', function() {
		var n = $(this).attr("name");
		var  a = $("#chkBox"+n).attr("checked");
		if( a == true)
		{
			$("#btnReset"+n).attr("disabled","true");
			$("#btnEdit"+n).attr("disabled","true");
			$("#hidchkbox"+n).val('checked');
			$("#btnEdit"+n).text("Edit");
			//$("#divText"+n).css("height","0 px");
			//$("#divText"+n).css("visibility","hidden");
			//$("#divText"+n).css("display","none");
		}
		else
		{
			$("#btnReset"+n).attr("disabled","");
			$("#btnEdit"+n).attr("disabled","");
			$("#hidchkbox"+n).val('');
		}
	});	
	function removeBlank(){
		$('[id^="chkBox"]').each(function(){
			var n = $(this).attr("name");
			var  a = $("#chkBox"+n).attr("checked");
			if(a==true)
			{
			  var b = $(this).parents('tr').prev().css("display","none");
			  var c = $(this).parents('tr').prev().prev().css("display","none");			  
			}
			var i=1;
			$('[id^="content"]').each(function(){
				var r = "#content"+i;
				var v = $(r).text();
				if(v=="")
				{
					$(r).parents('tr').prev().css("display","none");
					var s = "#rowindex"+i;
				}
				i++;
			});
		});
		$('[id^="controlpanel"]').remove();
		$('[id^="btnSpace"]').remove();
		//$('[id^="btnEdit"]').remove();
		//$('[id^="btnUpdate"]').remove();		
		$('[id^="span"]').remove();
		$('input:text').attr("dir","");
		$('input:text').css("width","25");
		$('input:text').css("border","0");
		
	}
	$('[id^="btnPreview"]').live('click', function() {
		var r = confirm("can you confirm this?");
		if(r==true)
		{
			removeBlank();
			$('#txt').html($('#divContent').html());
			$('.printable').print();
			$('#myForm').submit();
		}
	});
	$('[id^="grouptenantmasid"]').live('change', function() {		
		$('#grouptenant').empty();
		$('#divContent').empty();
		var str= $('#grouptenantmasid option:selected').text();		
		var temp = new Array();
		temp = str.split("-"); //split -
		temp = temp[1].split(")"); //split ')'
		temp[0]; // building shortname from lease name and tenant code
		var url="load_report.php?item=grouptenant&itemval="+$(this).val()+"&buildingshortname="+temp[0];	
		$.getJSON(url,function(data){
			$.each(data.error, function(i,response){
				$.each(data.myResult, function(i,response){
					$('#grouptenant').append(response.leasename+" <strong>("+response.shopcode+","+response.size+","+response.tenantcode+")<input type='hidden' name='tenantmasid"+response.tenantmasid+"' value='"+response.tenantmasid+"'><br><br>");					
				});
			});		
		});		
		
	});		
	$('[id^="rowindex"]').live('keyup', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	$('[id^="rowindex"]').live('blur', function() {
		var txtCount = $('#contentBody input:text').length;
		var a= $(this).val();
		var tr1 =$(this).parents('tr').html();
		var tr2 =$(this).parents('tr').next().html();
		var name = $(this).attr("name");
		if (a > txtCount || a==0)
		{
			alert("Not a valid index");
			this.value="";
			$(this).focus();
		}
		else
		{
			var i=1;
			var j=1;
			$('[id^="rowindex"]').each(function(){
			    var cname = "rowindex"+i;
				var b = "#rowindex"+i;
				if(cname != name)
				{
					if(j==a)
					{
						j++;
						//var km = i-1;
						//var b = "#rowindex"+km;
						//var id = "#"+name;
						//var trs1 = $(b).parents('tr').html();
						//var trs2 = $(b).parents('tr').next().html();
						//$(b).parents('tr').next().html(tr2);
						//$(b).parents('tr').html(tr1);
						//$(id).parents('tr').next().html(trs2);
						//$(id).parents('tr').html(trs1);
						//alert(b1);exit;
					}
					$(b).val(j);
					j++;
				}
				i++;
			});
		}
	});
});
</script>
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="#" method="post">
<div id="container">
	<br>
<h1>Lease</h1>
<br>
<table id="usertbl" class="table2" width="80%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Select Tenant 
			</th>
		</tr>
	</thead>
	<tbody>
	<tr>
		<td>
			Tenant
		</td>
		<td>			
			<select id="grouptenantmasid" name="grouptenantmasid" style='width:525px;'>
				<option value="" selected>----Select Group Tenant----</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Grouped Tenant(s)
		</td>
		<td id='grouptenant'>			
		</td>
	</tr>
	<tr>
		<td>
			<button class="example4demo">open window</button>
		</td>
		<td>
			<button type="button" id="btnView">View</button>
		</td>
	</tr>
	</tbody>
</table>
</div>
 <!--Main Div-->
<div id="divContent">
	
</div>
<textarea id='txt' name='txt'></textarea>
</form>
&nbsp;&nbsp;<font color=red><label id="cc"></label></font>
<br>
&nbsp;&nbsp;<font color=red><label id="st"></label></font>
</body>
</html>