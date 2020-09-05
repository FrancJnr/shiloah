<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Surrender of Lease</title>
        <!--<script src="../jquery/jquery-1.7.1.js" type="text/javascript"></script>-->
        <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="../shopstable.css">
        <script src="../js/jquery-2.1.4.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>  

<?php
	session_start();
	if (! isset($_SESSION['myusername']) ){
		header("location:../index.php");
	}
	include('../config.php');
	include('../MasterRef_Folder.php');
?>
<style>
	#sortable1, #sortable2 { list-style-type: none; margin: 0; padding: 0; zoom: 1; }
	#sortable1 li, #sortable2 li { margin: 0 5px 5px 5px; padding: 3px; width: 99%; }
	.ui-autocomplete {
		max-height: 150px;
		overflow-y: auto;
		/* prevent horizontal scrollbar */
		overflow-x: hidden;
	}
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
        $("#dateofsurrender").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"dd-mm-yy"
	});
	$("#grouptenantmasid").autocomplete({
                        source:"load_report.php?item=loadleaseforsurrender",
                        minLength:2,
			select: function(event, ui) {				
				$("#hid_itemid").val(ui.item.id);							
			}
                    });

//      function ajaxPOSTTest() {
//       // alert("haha");
//        try {
//            // Opera 8.0+, Firefox, Safari
//            ajaxPOSTTestRequest = new XMLHttpRequest();
//        } catch (e) {
//            // Internet Explorer Browsers
//            try {
//                ajaxPOSTTestRequest = new ActiveXObject("Msxml2.XMLHTTP");
//            } catch (e) {
//                try {
//                    ajaxPOSTTestRequest = new ActiveXObject("Microsoft.XMLHTTP");
//                } catch (e) {
//                    // Something went wrong
//                    alert("Your browser broke!");
//                    return false;
//                }
//            }
//        }
//
//        ajaxPOSTTestRequest.onreadystatechange = ajaxCalled_POSTTest;
//        
//    
//         disp_details();
//	var params =$("form").serialize();
//        var url = "save_surrender_lease.php?"+params;
//     // XMLHttpRequest
//        ajaxPOSTTestRequest.open("POST", url, true);
//        ajaxPOSTTestRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//        ajaxPOSTTestRequest.send(params);
//    }
//
//    //Create a function that will receive data sent from the server
//    function ajaxCalled_POSTTest() {
//        if (ajaxPOSTTestRequest.readyState == 4) {
////            document.getElementById("output").innerHTML = ajaxPOSTTestRequest.responseText;
//        //alert("Document Saved");
//       //document.getElementById('#the_iframe3').contentDocument.location.reload(true);
//        }
//    }
        

	//loadLeaseForSurrender("loadleaseforsurrender");
        function loadLeaseForSurrender(itemtype)
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
				    $('#grouptenantmasid').append( new Option(a.toUpperCase(),response.grouptenantmasid,true,false) );
			    });
                            
                     
                        
//                        arr.sort(function(o1, o2) {
//                        var t1 = o1.t.toLowerCase(), t2 = o2.t.toLowerCase();
//
//                        return t1 > t2 ? 1 : t1 < t2 ? -1 : 0;
//                      });
                            
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
	if($("#grouptenantmasid").val()== "")
	{
		alert("Please select tenant");
                return false;
	}			
//		alert($("#grouptenantmasid").html());
               // return;
              
   
        
        
        
        var a = confirm("continue to print surrender of lease?");
	if (a== true)
	{
                 disp_details();
		var dataToBeSent = $("form").serialize();
		window.open("save_surrender_lease.php?"+dataToBeSent, "windowOpenTab", "width=800,height=800,scrollbars=yes,resizable=yes,toolbar:yes");
                // window.open(url,   "width=800,height=800,scrollbars=yes,resizable=yes,toolbars:yes");
               //ajaxPOSTTest();
               //return false;		
        }else{
            
            return;
        }
    
            });



	$('[id^="btnPreview"]').live('click', function() {
		var r=confirm("Can you confirm this?");
		if (r == true)
		{
			removeBlank();
			$('#txt').html($('#divContent').html());		
			$('.printable').print();
			$('#myForm').submit(); // update content
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
	$('[id^="grouptenantmasid"]').live('blur', function() {				
		disp_details();
	});
	function disp_details()
	{
		$('#divContent').empty();
		//var str= $('#grouptenantmasid option:selected').text();
		var str= $('#grouptenantmasid').val();
		if(str=="")
		$('#grouptenant').empty();
		var temp = new Array();
		temp = str.split("-"); //split -
		temp = temp[1].split(")"); //split ')'
		temp[0]; // building shortname from lease name and tenant code
		var hidval = $("#hid_itemid").val();		
		var url="load_report.php?item=grouptenant&itemval='"+hidval+"'&buildingshortname="+temp[0];
		//$('#grouptenant').html(url);
		$.getJSON(url,function(data){
			$.each(data.error, function(i,response){
				$.each(data.myResult, function(i,response){
					$('#grouptenant').html("<a name='viewofferletter' id='viewofferletter' href='#'>"+response.leasename+" <strong>("+response.shopcode+","+response.size+","+response.tenantcode+")<input type='hidden' name='tenantmasid"+response.tenantmasid+"' value='"+response.tenantmasid+"'></a><br><br>");					
				});
			});		
		});
		return;
	}
	$('[id^="viewofferletter"]').live('click', function() {
		if($("#hid_itemid").val()== "")
		{
			alert("Please select tenant");return false;
		}		 		
		var dataToBeSent = $("form").serialize();
		window.open("view_surrender_tenant_details.php?"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
                return false;
	
		//alert($('#hid_itemid').val());
		//return false;
	});
});
</script>
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="#" method="post">
<div id="container">
	<br>
<h1>Surrender of Leasse</h1>
<br>
<table width="100%" class="table6">
	<thead>
		<tr>
			<th id="tblheader" align="left">
				Generate Surrender Of Lease 
			</th>
		</tr>
	</thead>
	<tbody>
	<tr>		
		<td>
			<span class='span_cont'>Tenant:</span></br>
			<input type="text" id="grouptenantmasid" name="grouptenantmasid" style="width:90%;"/>
			<input type="hidden" id="hid_itemid" name="hid_itemid" value="0"/>			
			</br></br>
			<span class='span_cont' id='grouptenant'></span>
		</td>
	</tr>	
        <tr>		
		<td>
			<span class='span_cont'>Date Of Surrender:</span></br>
			<input type='text' name='dateofsurrender' id='dateofsurrender' value='<?php  echo date('d-m-Y');?>'/>
		</td>
	</tr> 
	<tr>		
		<td>
			<button type="button" id="btnView">Surrender</button>
		</td>
	</tr>
	</tbody>
</table>
<div id="filedetails">
<div style='height:8px'></div>
<span class='span_cont'>Surrender Leases:</span><div style='height:8px'></div>
<?php
$directory="../../pms_docs/surrenders/";
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
