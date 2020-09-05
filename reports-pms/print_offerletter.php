<?php		
	include('../config.php');
	include('../MasterRef_Folder.php');
	if($_SERVER['REQUEST_METHOD'] == "POST")  
	{
		//print('<pre>');
		//print_r($_POST);
		//print('</pre>');
		
		//$post_tenantmasid = $_POST['grouptenantmasid'];
		//$txt = "'".mysql_real_escape_string($_POST['txt'])."'";
		//$update ="update rpt_lease set rowcontent = $txt where grouptenantmasid =$post_tenantmasid";
		//mysql_query($update);
		////echo $update;
		//echo "Data Updated Successfully"; 
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Offer Letter</title>
<script src="../js/jspdf/jspdf.debug.js"></script> 
<style>
	#sortable1, #sortable2 { list-style-type: none; margin: 0; padding: 0; zoom: 1; width: 100%; }
	#sortable1 li, #sortable2 li { margin: 0 5px 5px 5px; padding: 3px; width: 100%; }
</style>
<!--<script src="../js/jquery-1.9.1.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="../jqueryte/jquery-te-1.4.0.css">
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js" charset="utf-8"></script>
<script src="../js/jquery-2.1.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../jqueryte/jquery-te-1.4.0.min.js" charset="utf-8"></script>
<script type="text/javascript">
	$('.jqte-test').jqte();
	
	// settings of status
	var jqteStatus = true;
	$(".status").click(function()
	{
            alert("touche");
		jqteStatus = jqteStatus ? false : true;
		$('.jqte-test').jqte({"status" : jqteStatus})
	});
</script>
  -->
   
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
	loadTenantOfferletter("loadTenantDraft");	
	
	function loadTenantOfferletter(itemtype)
	{
		var url="load_report.php?item="+itemtype;	
		$.getJSON(url,function(data){
		    $.each(data.error, function(i,response){
			if(response.s == "Success")
			{
			    $('#grouptenantmasid').empty();
			    $('#grouptenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
			    $.each(data.myResult, function(i,response){
					var t = response.tradingname;
					var c ="";
					if(t !=""){
					    var c = " ( T/A "+ response.tradingname +" )";
					}
					    var b = response.leasename + c;
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
			    $('#grouptenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
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
		var url="save_print_offerletter.php";
		var dataToBeSent = $("form").serialize();
		window.open("save_print_offerletter.php?"+dataToBeSent,  "windowOpenTab", "width=1800,height=800,scrollbars=yes,resizable=yes,toolbars:yes");
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


//var doc = new jsPDF();
//var specialElementHandlers = {
//    '#divContent': function (element, renderer) {
//        return true;
//    }
//};
//
//$('#cmd').click(function () {
//    doc.fromHTML($('#myForm').html(), 15, 15, {
//        'width': 180,
//            'elementHandlers': specialElementHandlers
//    });
//    doc.output("dataurlnewwindow");
//   // doc.save('sample-file.pdf');
//});

//var source = window.document.getElementsByTagName("body")[0];
//doc.fromHTML(
//    source,
//    15,
//    15,
//    {
//      'width': 180,'elementHandlers': elementHandler
//    });
//
//doc.output("dataurlnewwindow");







});




</script>
        
        
<link href="style_progress.css" rel="stylesheet" type="text/css" /> 
</head>
<body id="dt_example" style="width:100%;">
<form id="myForm" name="myForm" action="#" method="post" style="width:100%;">
<!--<div id="containers">-->
	<br>
<center><h1>Offer Letter</h1></center><br>

<br>
<center><table id="usertbl" class="table1">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Generate Offer Letter 
			</th>
		</tr>
	</thead>
	<tbody>
	<tr>
		<td>			
			<select id="grouptenantmasid" name="grouptenantmasid" style='width:525px;'>
				<option value="" selected>----Select Group Tenant----</option>
			</select>
			</br></br>
				<span class='span_cont' id='grouptenant'></span>
		</td>
		
	</tr>	
	<tr>		
		<td colspan='2'>
			<button type="button" id="btnView">Create Offer Letter</button>
		</td>
           
	</tr>
	</tbody>
</table></center>


<div id="filedetails">
<div style='height:8px'>
<center><span class='span_cont'>Available Offer Letter's:</span><div style='height:8px'></div></center>
<?php
$directory="../../pms_docs/offerletters/";
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
</div> 
    <!--File Details-->
<div style='height:8px'>
<center><span class='span_cont'>Offer Letter's:</span><div style='height:8px'></div></center>
<?php
$directory2="../../pms_docs/offerletters/"; 
$sortOrder2="newestFirst"; 
$results2 = array(); 
$handler2 = opendir($directory2);
   
if (glob($directory2 . "*.*") != false)
{
    
while ($file2 = readdir($handler2)) {  
       if ($file2 != '.' && $file2 != '..' && $file2 != "robots.txt" && $file2 != ".htaccess"){ 
           $currentModified2 = filemtime($directory2."/".$file2);	   
	   $file_type2[] = strtolower(substr($directory2."/".$file2, strrpos($directory2."/".$file2, '.')+1));
	   $file_size2[] = filesize($directory2."/".$file2);
           $file_names2[] = $file2; 
           $file_dates2[] = $currentModified2; 
       }    
   } 
       closedir($handler2); 

   //Sort the date array by preferred order

   if ($sortOrder2 == "newestFirst"){ 
       arsort($file_dates2);
   }else{ 
       asort($file_dates2); 
   } 
    $w2=1;
   //Match file_names array to file_dates array 
   $file_names_Array2 = array_keys($file_dates2);     
   foreach ($file_names_Array2 as $idx2 => $name2) $name2=$file_names2[$name2]; 
   $file_dates2 = array_merge($file_dates2); 
   $i2 = 0;  $date12=0;
   $date2 = date('d-m-Y', $file_dates2[0]);   
   echo "<span class='span_cont'><u>".$date2."</u></span><div style='height:8px'></div>";
   echo "<table class='table6'>";
   echo "<tr>
		<th>S.No</th>
		<th>File</th>
		<th>Type</th>
		<th>Size</th>
		<th>Remove</th>
	</tr>";
   //Loop through dates array and then echo the list 
   foreach ($file_dates2 as $file_dates2){       
       $date2 = date('d-m-Y', $file_dates2);       
       $j2 = $file_names_Array2[$i2];       
       $file2 = $file_names2[$j2];
       $type2 = $file_type2[$j2];
       $size2 = $file_size2[$j2];
       if($type2=="btc")
	$type2 = "Dbf File";
       else if($type2=="txt")
	   $type2 = "Text File";
        else if($type2=="zip")
	  $type2 = "Zip File";
       
       $i2++;
       if($date2 < $date12)
       {
		echo "</table><div style='height:8px'></div>";
		echo "<span class='span_cont'><u>".$date2."</u></span><div style='height:8px'></div>";
		echo "<table class='table6'>";
		echo "<tr>
			<th>S.No</th>
			<th >File</th>
			<th>Type</th>
			<th>Size</th>
			<th>Remove</th>
			</tr>";
		$w2=1;
       }
       $link2 = $directory2.$file2;
       echo "<tr>";
       echo "<td>$w2</td>";
       echo "<td><a href='$link2' target='_blank'>$file2</a></td>";
       echo "<td>$type2</td>";
       echo "<td>".formatSizeUnits2($size2)."</td>";       
       echo "<td align='center'><a href=\"deletefile.php?file=$link2\"><img src='../images/delete.png'></a></td>";
       echo "</tr>";
       //echo  "File name: $file - Date Added:  $date'. $i <br/>";
       $w2++;
       $date12 = $date2;
   }
}else
{
	echo "No Files in the directory.";
}

function formatSizeUnits2($bytes2)
    {
        if ($bytes2 >= 1073741824)
        {
            $bytes2 = number_format($bytes2 / 1073741824, 2) . ' GB';
        }
        elseif ($bytes2 >= 1048576)
        {
            $bytes2 = number_format($bytes2 / 1048576, 2) . ' MB';
        }
        elseif ($bytes2 >= 1024)
        {
            $bytes2 = number_format($bytes2 / 1024, 2) . ' KB';
        }
        elseif ($bytes2 > 1)
        {
            $bytes2 = $bytes2 . ' bytes';
        }
        elseif ($bytes2 == 1)
        {
            $bytes2 = $bytes2 . ' byte';
        }
        else
        {
            $bytes2 = '0 bytes';
        }

        return $bytes2;
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

<!--<button id="cmd">generate PDF</button>-->
</body>

</html>