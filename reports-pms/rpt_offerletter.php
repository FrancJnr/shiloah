<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Offer Letter</title>
<?php
session_start();
	if (! isset($_SESSION['myusername']) ){
		header("location:../index.php");
	}
	include('../config.php');
	include('../MasterRef_Folder.php');
//try
//{
//    //$to = "dipak@shiloahmega.com,mithesh@shiloahmega.com";
//    $to = "juma@shiloahmega.com";
//    $subject = "Test Report Mail - PMS MODULE";
//     $message = "Dear All! <br> <br>This is a Automated Email message from PMS Module.<br><br>After Implementing the PMS module the reports can be viwed in the"
//                ." PROGRAM as well as a <br><br> <b>Remainder Email</b> will be sent to the corrensponding persons till the task completed."
//		."<br><br>Regards <br><br><b>Prbhu.</b>";
//     // Always set content-type when sending HTML email
//    $headers   = array();
//    $headers[] = "MIME-Version: 1.0";
//    $headers[] = "Content-type: text/html; charset=iso-8859-1";
//    $headers[] = "From: Prabhu <juma@shiloahmega.com>";    
//    //$headers[] = "Cc:  Arul Raj <arulraj@shiloahmega.com>,Muthukumar <muthukumar@shiloahmega.com>,Prabhu <juma@shiloahmega.com>";
//    $headers[] = "Reply-To: Prabhu <juma@shiloahmega.com>";
//    $headers[] = "Subject: {$subject}";
//    $headers[] = "X-Mailer: PHP/".phpversion();
//    ini_set('SMTP','192.168.0.1');// DEFINE SMTP MAIL SERVER
//    mail($to,$subject,$message,implode("\r\n", $headers));
//    $result= "Mail Sent";
//}
//catch(Exception $e)
//{
//    $result= $e->getMessage();
//}
//echo $result;
//exit;

		
		if($_SERVER['REQUEST_METHOD'] == "POST")  
		{
			$sqlGet ="";
			//foreach ($_POST as $key => $entry)
			//{
			  //   if(is_array($entry)){
			    //   print $key . ": " . implode(',',$entry) . "<br>";
			     //}
			     //else {
			      // print $key . ": " . $entry . "<br>";
			    // }
			//}
			//print('<pre>');
			//print_r($_POST);
			//print('</pre>');
			//exit;
			
			//$nk =0;
			//foreach ($_POST as $k=>$v) {
			//    $sqlGet.= $nk."; Name: ".$k."; Value: ".$v."<BR>";
			//    $nk++;
			//}
			//$custom = array('msg'=> $sqlGet ,'s'=>'error');
			//$response_array[] = $custom;
			//echo '{"error":'.json_encode($response_array).'}';
			//exit;
			$post_tenantmasid = $_POST['grouptenantmasid'];
			$sql = "SELECT tenantmasid FROM  `group_tenant_mas` WHERE  `grouptenantmasid` ='$post_tenantmasid';";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result))
			{
				$post_tenantmasid  = $row["tenantmasid"];
			}
			
			$var = "NO DATA FOUND FOR UPDATE";
			$i=1;
			$update="";
			foreach ($_POST as $key => $val){
				$str =substr($key, 0,11);
				$strindex = substr($key, 11,5);//take no from string
				if($str=="rowindexval")
				{
					if($val !="")
					{
						$update ="update rpt_offerletter set rowindex$strindex ='".mysql_real_escape_string(floatval($val))."' where tenantmasid =".$post_tenantmasid." and active=1;";
						////echo $update."<br><br>";
						mysql_query($update);
						$var = mysql_error();
					}
					$i++;
					
				}
			}
			//$custom = array('msg'=> $update ,'s'=>'error');
			//$response_array[] = $custom;
			//echo '{"error":'.json_encode($response_array).'}';
			//exit;
			$i=1;
			$key="";
			$val="";
			foreach ($_POST as $key => $val){
				$str =substr($key, 0,10); 
				if($str=="hidcontent")
				{
					if($val !="")
					{
						$update ="update rpt_offerletter set row$i ='".mysql_real_escape_string($val)."' where tenantmasid =".$post_tenantmasid." and active=1";
						//echo $update."<br><br>";
						mysql_query($update);
						$var = mysql_error();
					}
					$i++;
					
				}
			}
			//$custom = array('msg'=> $update ,'s'=>'error');
			//$response_array[] = $custom;
			//echo '{"error":'.json_encode($response_array).'}';
			//exit;
			$i=1;
			$key="";
			$val="";
			foreach ($_POST as $key => $val){
				$str =substr($key, 0,9); 
				if($str=="hidchkbox")
				{
					$update ="update rpt_offerletter set removerow$i ='".mysql_real_escape_string($val)."' where tenantmasid =".$post_tenantmasid." and active=1";
					//echo $update."<br><br>";
					mysql_query($update);
					$var = mysql_error();
					$i++;
				}
			}
			if ($var =="")
			$var ="Data Updated Successfully";
			
			$custom = array('msg'=> $var,'s'=>'ok');
			$response_array[] = $custom;
			echo $var;
			//exit;
		}
?>
<style>
	#sortable1, #sortable2 { list-style-type: none; margin: 0; padding: 0; zoom: 1; }
	#sortable1 li, #sortable2 li { margin: 0 5px 5px 5px; padding: 3px; width: 99%; }
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	loadTenantDraft("loadTenantDraft");
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
        $("#btnClear").live("click",function(){
            e.preventDefault();
            editor1.focus();
            editor1.clear();
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
	$('[id^="btnReset"]').live('click', function() {
		//var n = $(this).attr("name")
		//var url="save_rpt_offerletter.php?item=reset"+n;
		//var a = confirm("can you confirm this?")
		//if(a == true)
		//{
		//	var dataToBeSent = $("form").serialize();
		//	$.getJSON(url,dataToBeSent, function(data){
		//		$.each(data.error, function(i,response){
		//			if(response.s == "Success")
		//			{
		//				var a = response.p;
		//				$("#p"+n).html(a);
		//			}				
		//			else
		//			{
		//				alert(response.s);
		//			}
		//		});             
		//	});
		//$("#editor"+n).empty();
		//}
	});
	$('[id^="offerlettertype"]').live('click', function() {
		$('#grouptenant').empty();
		var a = $('input[name=offerlettertype]:checked').val();
		if(a == "Draft")
		{
			loadTenantDraft("loadTenantDraft");
		}
		else
		{
			loadTenantDraft("loadTenantFinalized");
		}
	});
	function loadTenantDraft(itemtype)
	{
		//var url="load_report.php?item="+itemtype;	
		//$.getJSON(url,function(data){
		//	$.each(data.error, function(i,response){
		//		if(response.s == "Success")
		//		{
		//			$('#tenantmasid').empty();
		//			$('#tenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
		//			$.each(data.myResult, function(i,response){
		//				var a = response.leasename+" ("+response.shopcode+")";
		//				$('#tenantmasid').append( new Option(a,response.tenantmasid,true,false) );
		//			});
		//		}
		//		else
		//		{
		//			$('#tenantmasid').empty();
		//			$('#tenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
		//			alert(response.s);
		//		}
		//	});		
		//});
		var url="load_report.php?item="+itemtype;	
		$.getJSON(url,function(data){
		    $.each(data.error, function(i,response){
			if(response.s == "Success")
			{
			    $('#grouptenantmasid').empty();
			    $('#grouptenantmasid').append( new Option("-----Select Group Tenant-----","",true,false) );
			    $.each(data.myResult, function(i,response){
				    var r = response.renewalfromid;
				    if(r <=0)
				    var a = response.leasename+" ("+response.shopcode+")";
				    else
				    var a = response.leasename+" ("+response.shopcode+" RENEWED)" ;
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
	var k = $(":checkbox").filter(":checked").size(); //find no of checked checkbox
	
	//if (k >0)
	//{
		var r=confirm("Can you confirm this?");
		if (r == true)
		{
			//alert("hi");exit;
			$('#alterOfferletterTbl').css("display","");
			////var editor1 = $("#editor1").cleditor(options)[0];
			var url="save_rpt_offerletter.php";
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#divContent').html(response.divContent);
						$('[id^="chkBox"]').each(function(){
							var n = $(this).attr("name");
							var  a = $("#chkBox"+n).attr("checked");
							if( a == true)
							{
								$("#btnReset"+n).attr("disabled","true");
								$("#btnEdit"+n).attr("disabled","true");
								$("#hidchkbox"+n).val('checked');							
							}
							else
							{
								$("#btnReset"+n).attr("disabled","");
								$("#btnEdit"+n).attr("disabled","");
								$("#hidchkbox"+n).val('');
							}
						});
						//$( "#sortable1, #sortable2" ).sortable({
						//	connectWith: ".connectedSortable",
						//	placeholder: "ui-state-highlight"
						//}).disableSelection();
						$( "#sortable1" ).sortable({
							items: "li:not(.ui-state-disabled)",
							placeholder: "ui-state-highlight",
							create:function(){
								$('#cc').html("");
							},
							update:function(){
								var k = $('[id^="rowindex"]').text();
								var arr = $('[id^="rowindex"]').text().split('.');
								var i = arr.length;
								i = i-1;
								var args="";
								var n=1;
								var s ="";
								$('[id^="rowindex"]').each(function(){
									s += $('[id^="rowindex"]').attr('name') +","
								});
								for(var j=0;j<i;j++)
								{
									args += arr[j]+",->"+n+" ";
									var r = "#rowindexval"+arr[j];
									var modifiedrowval = n+".";
									$(r).val(modifiedrowval);
									n++;
								}
							}
						}).disableSelection();
					}				
					else
					{
						$('#divContent').html(response.msg);
					}
					});             
			});
		}
	//}//check box checked
	//else
	//{
	//	alert("Please select a tenant checkbox.");		
	//}
	});
	$('[id^="btnBreak"]').live('click', function() {
		var a =$(this).parents('tr').next().find('td').html();
		a = a +"          <br>";
		$(this).parents('tr').next().find('td').html(a);
		//return false;
	});
	$('[id^="btnRemoveBreak"]').live('click', function() {
		var a =$(this).parents('tr').next().find('td').html().replace('          <br>','');
		$(this).parents('tr').next().find('td').html(a);
		//return false;
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
			$('[id^="controlpanel"]').remove();
			$('[id^="btnSpace"]').remove();
			$('input:text').attr("dir","");
			$('input:text').css("width","25");
			$('input:text').css("border","0");
		});
		
	}
	$('[id^="btnPreview"]').live('click', function() {
		removeBlank();
		$('.printable').print();
	});
	$('[id^="btnPrint"]').live('click', function() {
		var r = confirm("can you confirm this?");
		if(r==true)
		{
			removeBlank();
			$('.printable').print();
			var a = $('input[name=offerlettertype]:checked').val();
			if(a == "Draft")
			{
				$('#myForm').submit();
			}
		}
		else{alert("Action cancelled");}
	});
	$('[id^="btnRemoveRow"]').live('click', function() {
		var r = confirm("can you confirm this");
		if (r==true)
		{
			$(this).parents('tr').prev().remove();
			$(this).closest('tr').remove();
		}
	});
	$(':checkbox[readonly="readonly"]').click(function() {
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
					//$('#grouptenant').append(response.leasename+" <strong>("+response.shopcode+","+response.size+","+response.tenantcode+")<br><br>");
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
	$('[id^="btnAddNew"]').live('click', function() {
		var r = confirm("can you confirm this");
		if (r==true)
		{
			var $tbody = $("#tbldata tbody:last");
			var txtCount = $('#contentBody input:text').length +1;
			var newId = +($tbody.find("tr:last").attr("id") || "row0").substr(3) + txtCount;
			var trid = "row"+newId;
			var tdid = "content"+newId;
			newId1 =  newId +1;
			var trid1 = "row"+newId1;
			var tdid1 = "content"+newId1;
			var row1 = "<tr id="+trid+"><td id="+tdid+">----------------------------</td></tr>";
			var contols ="Row Index:<input type='text' id='rowindex"+newId+"' dir=' rlt' name='"+newId+"' value='"+newId+"' style='width:40px'/>"+
						"<button type='button' id='btnEdit"+newId+"' name='"+newId+"'>Edit</button>"+
						" &nbsp<button type='button' id='btnReset"+newId+"' name='"+newId+"'>Reset</button>"+
						" &nbspDisable this<input type='checkbox' id='chkbox"+newId+"' name='"+newId+"' />"+
						"<button type='button' id='btnRemoveRow"+newId+"' name='"+newId+"'>Remove Row</button>"+
						"&nbsp<div id='divText"+newId+"' style='height: 0px;visibility: hidden;'>"+
						"<textarea id='editor"+newId+"' rows='0' cols='0'></textarea>"+
						"<button type='button' id='btnUpdate"+newId+"' name='"+newId+"'>Update</button>"+
						"<input type='hidden' id='hidcontent"+newId+"' name='hidcontent"+newId+"' >"+
						"</div>";
			var row2 = "<tr id="+trid1+"><td id="+tdid1+">"+contols+"</td></tr>";
			$('#tbldata').append(row1);
			$('#tbldata').append(row2);
			alert("New row added bottom of the table");
			$('input:last').focus();
		}
	});	
});
</script>
<link href="style_progress.css" rel="stylesheet" type="text/css" /> 
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="#" method="post">
<div id="container">
	<br>
<h1>Offerletter</h1>
<br>
<table id="usertbl" class="table2" width="80%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Generate Offerletter
			</th>
		</tr>
	</thead>
	<tbody>
	 <tr  align='center'>
		<td>
			Draft<input type="radio" id="offerlettertype" name="offerlettertype" value="Draft" checked/> |
			Finalized<input type="radio" id="offerlettertype" name="offerlettertype" value="Finalized " /> 
		</td>
	</tr>
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
		<td>
			<button type="button" id="btnView">Create Offerletter</button>
		</td>
	</tr>
	</tbody>
</table>
<div id="filedetails">
<div style='height:8px'></div>
<span class='span_cont'>Avilable offerletters:</span><div style='height:8px'></div>
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
   echo "<table>";
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
		echo "<table border=1'>";
		echo "<tr>
			<th>S.No</th>
			<th >File</th>
			<th>Type</th>
			<th>Size</th>
			<th>Remove</th>
			</tr>";
		$w=1;
       }
       $link = "../offerletters/$file";
       echo "<tr>";
       echo "<td>$w</td>";
       echo "<td><a href='$link' target='_blank'>$file</a></td>";
       echo "<td>$type</td>";
       echo "<td>".formatSizeUnits($size)."</td>";
       $link = "../offerletters/$file";
       echo "<td align='center'><a href=\"deletefile.php?file=$link\"><img src='delete.png'></a></td>";
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
<input  type='hidden'   id="hidcontent1"  name="hidcontent1" value="" />
<input  type='hidden'   id="hidcontent2"  name="hidcontent2" value="" />
<input  type='hidden'   id="hidcontent3"  name="hidcontent3" value="" />
<input  type='hidden'   id="hidcontent4"  name="hidcontent4" value="" />
<input  type='hidden'   id="hidcontent5"  name="hidcontent5" value="" />
<input  type='hidden'   id="hidcontent6"  name="hidcontent6" value="" />
<input  type='hidden'   id="hidcontent7"  name="hidcontent7" value="" />
<input  type='hidden'   id="hidcontent8"  name="hidcontent8" value="" />
<input  type='hidden'   id="hidcontent9"  name="hidcontent9" value="" />
<input  type='hidden'   id="hidcontent10"  name="hidcontent10" value="" />
<input  type='hidden'   id="hidcontent11"  name="hidcontent11" value="" />
<input  type='hidden'   id="hidcontent12"  name="hidcontent12" value="" />
<input  type='hidden'   id="hidcontent13"  name="hidcontent13" value="" />
<input  type='hidden'   id="hidcontent14"  name="hidcontent14" value="" />
<input  type='hidden'   id="hidcontent15"  name="hidcontent15" value="" />
<input  type='hidden'   id="hidcontent16"  name="hidcontent16" value="" />
<input  type='hidden'   id="hidcontent17"  name="hidcontent17" value="" />


<input  type='hidden'   id="hidchkbox1"  name="hidchkbox1" value="" />
<input  type='hidden'   id="hidchkbox2"  name="hidchkbox2" value="" />
<input  type='hidden'   id="hidchkbox3"  name="hidchkbox3" value="" />
<input  type='hidden'   id="hidchkbox4"  name="hidchkbox4" value="" />
<input  type='hidden'   id="hidchkbox5"  name="hidchkbox5" value="" />
<input  type='hidden'   id="hidchkbox6"  name="hidchkbox6" value="" />
<input  type='hidden'   id="hidchkbox7"  name="hidchkbox7" value="" />
<input  type='hidden'   id="hidchkbox8"  name="hidchkbox8" value="" />
<input  type='hidden'   id="hidchkbox9"  name="hidchkbox9" value="" />
<input  type='hidden'   id="hidchkbox10"  name="hidchkbox10" value="" />
<input  type='hidden'   id="hidchkbox11"  name="hidchkbox11" value="" />
<input  type='hidden'   id="hidchkbox12"  name="hidchkbox12" value="" />
<input  type='hidden'   id="hidchkbox13"  name="hidchkbox13" value="" />
<input  type='hidden'   id="hidchkbox14"  name="hidchkbox14" value="" />
<input  type='hidden'   id="hidchkbox15"  name="hidchkbox15" value="" />
<input  type='hidden'   id="hidchkbox16"  name="hidchkbox16" value="" />
<input  type='hidden'   id="hidchkbox17"  name="hidchkbox17" value="" />

</form>
&nbsp;&nbsp;<font color=red><label id="cc"></label></font>
<br>
&nbsp;&nbsp;<font color=red><label id="st"></label></font>
</body>
</html>