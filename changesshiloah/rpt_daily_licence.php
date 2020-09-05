<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
            header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');		
    //include('');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>DAILY OCCUPATION - LICENSE AGREEMENT</title>
        
<!--<script src="../jquery/jquery-1.7.1.js" type="text/javascript"></script>
<script src="../js/jspdf/jspdf.min.js" type="text/javascript"></script>-->

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
	loadDailyTenant();
	function loadDailyTenant()
	{
		itemtype ="loadDailyTenant"
		var url="load_report.php?item="+itemtype;	
		$.getJSON(url,function(data){
			$.each(data.error, function(i,response){
				if(response.s == "Success")
				{
					$('#licensemasid').empty();
					$('#licensemasid').append( new Option("-----Select Daily Tenant-----","",true,false) );
					$.each(data.myResult, function(i,response){
						var a = response.licensename+"("+response.licensecode+")";
                                                $('#licensemasid').append( new Option(a,response.licensemasid,true,false) );
					});
				}
				else
				{
					$('#licensemasid').empty();
					$('#licensemasid').append( new Option("-----Select Daily Tenant-----","",true,false) );
					alert(response.s);
				}
			});		
		});
	}
        $('[id^="btnEditPreview"]').live('click', function() {
		if($("#licensemasid option:selected").val()== "")
		{
			alert("Please select tenant");
			$("#licensemasid")[0].focus();return false;
		}
	         var url="save_print_daily_license.php?";
		var dataToBeSent = $("form").serialize();
		window.open(url+dataToBeSent,  "windowOpenTab", "width=1800,height=800,scrollbars=yes,resizable=yes,toolbars:yes");
                return false;	
	});
	$('[id^="btnView"]').live('click', function() {
		if($("#licensemasid option:selected").val()== "")
		{
			alert("Please select tenant");
			$("#licensemasid")[0].focus();return false;
		}
		var url="save_daily_licence.php";
		var dataToBeSent = $("form").serialize();		
		$.getJSON(url,dataToBeSent, function(data){
			$.each(data.error, function(i,response){
				if(response.s == "Success")
				{
					$('#divContent').html(response.divContent);
				}
				else
				{
					$('#cc').html(response.msg);	
				}				
			});
		});
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
	$('[id^="btnPreview"]').live('click', function() {
		removeBlank();
		$('.printable').print();
           
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
		$('[id^="btnEdit"]').remove();
		$('[id^="btnUpdate"]').remove();
		$('input:text').attr("dir","");
		$('input:text').css("width","25");
		$('input:text').css("border","0");
		
	}
});
</script>
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="#" method="post">
<!--<div id="container">-->
	<br>
<h1>Daily License</h1>
<br>
<table id="usertbl" class="table2" width="80%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Select Daily Tenant 
			</th>
		</tr>
	</thead>
	<tbody>
	<tr>
		<td>
			Daily Tenant
		</td>
		<td>
			<select id="licensemasid" name="licensemasid" style='width:525px;'>
				<option value="" selected>----Select Daily Tenant----</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			
		</td>
		<td>
			<button type="button" id="btnView">View</button>
                        <button type="button" id="btnEditPreview">View & Print</button>
		</td>
	</tr>
	</tbody>
</table>
<!--</div>-->
 <!--Main Div-->
<div id="divContent">
	
</div>
<div id="filedetails">
<div style='height:8px'></div>
<span class='span_cont'>Available Leases:</span><div style='height:8px'></div>
<?php
$directory="../../pms_docs/dailylicense/";
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

<!--</div>-->
 <!--Main Div-->
<div id="divContent">
	
</div>
</form>
&nbsp;&nbsp;<font color=red><label id="cc"></label></font>
<br>
&nbsp;&nbsp;<font color=red><label id="st"></label></font>
</body>
</html>