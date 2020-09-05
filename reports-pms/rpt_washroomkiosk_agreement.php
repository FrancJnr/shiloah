<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Washroom Agreement</title>
<?php
		session_start();
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
			$update ="update rpt_washroomkiosk_agreement set rowcontent = $txt where grouptenantmasid =$post_tenantmasid";
			mysql_query($update);
			//echo $update;
			echo "Data Updated Successfully"; 
		}
?>
<style>
	#sortable1, #sortable2 { list-style-type: none; margin: 0; padding: 0; zoom: 1; }
	#sortable1 li, #sortable2 li { margin: 0 5px 5px 5px; padding: 3px; width: 99%; }
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {	
     var options = {
            width: 1200,
            height: 300,
            controls: "bold italic underline strikethrough subscript superscript | font size " +
                    "style | color highlight removeformat | bullets numbering | outdent " +
                    "indent | alignleft center alignright justify | undo redo | " +
                    "rule link unlink | cut copy paste pastetext "
        };       
        loadKWTenant("loadKioskAndWashroomTenant");
        function loadKWTenant(itemtype)
	{		
		var url="load_report.php?item="+itemtype;	
		$.getJSON(url,function(data){
		    $.each(data.error, function(i,response){
			if(response.s == "Success")
			{
			    $('#grouptenantmasid').empty();
			    $('#grouptenantmasid').append( new Option("-----Select Washroom Tenant-----","",true,false) );
			    $.each(data.myResult, function(i,response){				    
				    //var a = response.leasename+" ("+response.shopcode+")";
				    //var b = response.renewalfromid;
				    //if(b>0)
				    //a =a+ " [Renewed]**";
					var t = response.tradingname;
					if(t =="")
					    var b = response.leasename;
					else
					    var b = response.leasename +" (T/A) " +response.tradingname;
					var r = response.renewalfromid;
					if(r <=0)
					    var a = b+" ("+response.shopcode+")";
					else
					var a = b+" ("+response.shopcode+" RENEWED)" ;
					
				$('#grouptenantmasid').append( new Option(a,response.grouptenantmasid,true,false) );
			    });
			}
			else
			{
			    $('#grouptenantmasid').empty();
			    $('#grouptenantmasid').append( new Option("-----Select Washroom Tenant-----","",true,false) );
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
		var url="save_washroomkiosk_agreement.php";
		var dataToBeSent = $("form").serialize();		
		$.getJSON(url,dataToBeSent, function(data){
			$.each(data.error, function(i,response){				
				if(response.s == "Success")
				{
					$('#divContent').html(response.divContent);
				}
				else
				{
					$('#divContent').html(response.divContent);
				}
			});
		});	
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
		removeBlank();
		$('#txt').html($('#divContent').html());
		$('.printable').print();
		$('#myForm').submit();
	});
	$('#btnPrint').click(function(){
		if($("#grouptenantmasid option:selected").val()== "")
		{
			alert("Please select tenant");return false;
		}		 
		//var url="save_print_washroomkiosk_agreement.php";
		var dataToBeSent = $("form").serialize();
		window.open("save_print_washroomkiosk_agreement.php?"+dataToBeSent,  "windowOpenTab", "width=1800,height=800,scrollbars=yes,resizable=yes,toolbars:yes");
                return false;	
     
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
});
</script>
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="#" method="post">
<div id="container">
	<br>
<h1>Washroom Agreement</h1>
<br>
<table id="usertbl" class="table2" width="80%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Select Washroom Tenant 
			</th>
		</tr>
	</thead>
	<tbody>
	<tr>
		<td>
			Tenant <font color='red'>*</font>
		</td>
		<td>
			<select id="grouptenantmasid" name="grouptenantmasid" style='width:525px;'>
				<option value="" selected>----Select Washroom Tenant----</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Select Tenant(s)
		</td>
		<td id='grouptenant'>			
		</td>
	</tr>
	<tr>
		<td>
			
		</td>
		<td>
			<button type="button" id="btnView">View</button>
			<button type="button" id="btnPrint">Print</button>
		</td>
	</tr>
	</tbody>
</table>
<div id="filedetails">
<div style='height:8px'></div>
<span class='span_cont'>Available Leases:</span><div style='height:8px'></div>
<?php
$directory="../../pms_docs/washroom/";
$sortOrder="newestFirst"; 
$results = array(); 
$handler = opendir($directory);
   
if (glob($directory . "*.*") != false)
{
    
while ($file = readdir($handler)) {  
       if ($file != '.' && $file != '..' && $file != "robots.txt" && $file != ".htaccess"){ 
           $currentModified = filemtime($directory."/".$file);	   
	   $file_type[] = strtolower(substr($directory."/".$file, strrpos($directory."/".$file, '.')+1));
	   $file_size[] = filesize($directory."/".$file);
           $file_names[] = $file; 
           $file_dates[] = $currentModified; 
       }    
   } 
       closedir($handler); 

   //Sort the date array by preferred order

   if ($sortOrder == "newestFirst"){ 
       arsort($file_dates);
   }else{ 
       asort($file_dates); 
   } 
    $w=1;
   //Match file_names array to file_dates array 
   $file_names_Array = array_keys($file_dates);     
   foreach ($file_names_Array as $idx => $name) $name=$file_names[$name]; 
   $file_dates = array_merge($file_dates); 
   $i = 0;  $date1=0;
   $date = date('d-m-Y', $file_dates[0]);   
   echo "<span class='span_cont'><u>".$date."</u></span><div style='height:8px'></div>";
   echo "<table class='table6'>";
   echo "<tr>
		<th>S.No</th>
		<th>File</th>
		<th>Type</th>
		<th>Size</th>
		<th>Remove</th>
	</tr>";
   //Loop through dates array and then echo the list 
   foreach ($file_dates as $file_dates){       
       $date = date('d-m-Y', $file_dates);       
       $j = $file_names_Array[$i];       
       $file = $file_names[$j];
       $type = $file_type[$j];
       $size = $file_size[$j];
       if($type=="btc")
	$type = "Dbf File";
       else if($type=="txt")
	   $type = "Text File";
        else if($type=="zip")
	  $type = "Zip File";
       
       $i++;
       if($date < $date1)
       {
		echo "</table><div style='height:8px'></div>";
		echo "<span class='span_cont'><u>".$date."</u></span><div style='height:8px'></div>";
		echo "<table class='table6'>";
		echo "<tr>
			<th>S.No</th>
			<th >File</th>
			<th>Type</th>
			<th>Size</th>
			<th>Remove</th>
			</tr>";
		$w=1;
       }
       $link = $directory.$file;
       echo "<tr>";
       echo "<td>$w</td>";
       echo "<td><a href='$link' target='_blank'>$file</a></td>";
       echo "<td>$type</td>";
       echo "<td>".formatSizeUnits($size)."</td>";       
       echo "<td align='center'><a href=\"deletefile.php?file=$link\"><img src='../images/delete.png'></a></td>";
       echo "</tr>";
       //echo  "File name: $file - Date Added:  $date'. $i <br/>";
       $w++;
       $date1 = $date;
   }
}else
{
	echo "No Files in the directory.";
}
function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}

?>
</div> <!--File Details-->

</div>
 <!--Main Div-->
<div id="divContent">
	
</div>
</form>
&nbsp;&nbsp;<font color=red><label id="cc"></label></font>
<br>
&nbsp;&nbsp;<font color=red><label id="st"></label></font>
</body>
</html>