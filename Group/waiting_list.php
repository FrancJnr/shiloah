<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Group Offer Letter</title>
        <link rel="stylesheet" type="text/css" href="../styles.css">
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
	//echo $_SESSION['usermasid'].$_SESSION['myusername'].$_SESSION['employeemasid'];
    include('../config.php');
    include('../MasterRef_Folder.php');    
    function loadbuilding()
	{
		$companymasid = $_SESSION['mycompanymasid'];
		$sql = "select buildingmasid, buildingname from mas_building where companymasid = '$companymasid' order by companymasid";
		$result = mysql_query($sql);
		if($result != null)
		{
			while($row = mysql_fetch_assoc($result))
			{
				//echo("<option value=".$row['buildingmasid'].">".$row['buildingname']."</option>");
				echo($row['buildingname']."<input type='checkbox' id=".$row['buildingmasid']." name=".$row['buildingmasid']."> &nbsp;&nbsp");		
			}
		}
	}
	 function getUserDept()
	{
		$username = $_SESSION['myusername'];
		$departmentid=0;
		//$departmentname=0;
		$sql = "select departmentmasid from mas_user where username like '%$username%' LIMIT 1";
		
		$result = mysql_query($sql);
		if($result != null)
		{
			while($row = mysql_fetch_assoc($result))
			{
				
				$departmentid=$row['departmentmasid'];		
			}
		}
		return $departmentid;
	}
	//echo  getUserDept();
?>
<style>
    #sortable1, #sortable2 { list-style-type: none; margin: 0; padding: 0; zoom: 1; }
    #sortable1 li, #sortable2 li { margin: 0 5px 5px 5px; padding: 3px; width: 99%; }
    .selectbox{
	width:120px;
	background-color:gold;
	border:0px;
	color:navy;
	text-align:left;
	font-size:11pt;
	font-family:Times;
	}
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    generate_tenancyrefcode(0);
    loadrunningtenant(0);
    loadwaitinglist(0);
    // TENANCY REG CODE GENERATOR
    function generate_tenancyrefcode(buildingmasid)
    {
	var url="load_list.php?item=generate_tenancyrefcode&buildingmasid="+buildingmasid;		
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {                 
                    $.each(data.myResult, function(i,response){								
			//$('#cc').append(response.sqlcodeinsert+"<br>");
			//$('#cc').append(response.codestatus+"<br>");			
                    });
                }           
            });		
        });
    }
    function loadrunningtenant(buildingmasid)
    {
        var url="load_list.php?item=runningtenant&buildingmasid="+buildingmasid;		
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {
                    $('#grouptenantmasid').empty();
                    $('#grouptenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
                    $.each(data.myResult, function(i,response){			
			var t = response.tradingname;
			if(t !="")
			     var b = response.leasename+" (T/A)"+response.tradingname;
			else
			    var b = response.leasename;
			    
			var r = response.renewalfromid;
			if(r <=0)
			    var a = b+" ("+response.shopcode+")";
			else
			var a = b+" ("+response.shopcode+" RENEWED)" ;
			a = a+ " ("+response.doc+")";
                        $('#grouptenantmasid').append( new Option(a,response.grouptenantmasid,true,false) );			
                    });
                }
                else
                {
                    $('#grouptenantmasid').empty();
                    $('#grouptenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
                    //$('#cc').html(response.msg);
                }
            });		
        });
    }
    function loadwaitinglist(buildingmasid)
    {        
	var url="load_list.php?item=waitinglist&buildingmasid="+buildingmasid;	
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {
                    $('#tenantmasid').empty();
                    $('#tenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
                    $.each(data.myResult, function(i,response){			
			var t = response.tradingname;
			if(t !="")
			     var b = response.leasename+" (T/A)"+response.tradingname;
			else
			    var b = response.leasename;
			var r = response.renewalfromid;
			if(r <=0)
			    var a = b+" ("+response.shopcode+")";
			else
				var a = b+" ("+response.shopcode+" RENEWED)" ;
			a = a+ " ("+response.doc+")";
                        $('#tenantmasid').append( new Option(a,response.grouptenantmasid,true,false) );
                    });
                }
                else
                {
                    $('#tenantmasid').empty();
                    $('#tenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
                    //$('#cc').html(response.msg);
                }
            });		
        });
    }

        $('#btnInclude').live('click', function() {
	$('#divContent').html("");
	var pinlen  = jQuery.trim($('#pinno').val());
	if(pinlen.length<11)
	{
		alert("Please Enter Pinno with 11 characters.");
		$('#pinno').val(pinlen);
		$('#pinno').focus();
		return false;
	}
	var a = $("#tenantmasid option:selected").val();
        if((a == null) || (a==""))
	{
	    alert("Please select tenant");return false;
	}
	if($('#pinno').val() =="" )
	{
		alert("Please enter pinno");
		$('#pinno').focus();
		return false;
	}
	if($('#tenancyrefcode').val() =="" )
	{
		alert("Please enter tenancyrefcode");
		$('#tenancyrefcode').focus();
		return false;
	}
        var r=confirm("Can you confirm this?");
	if (r == true)
	{	    	    
	    var url="save_list.php?action=include";
	    var dataToBeSent = $("#form1").serialize();
	    $.getJSON(url,dataToBeSent, function(data){
		$.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {
			$('#divContent').html(response.divContent);
			////generate_tenancyrefcode(0);
			loadrunningtenant();
			
                       // alert('Be back1');
		    }	
		    else
		    {
			$('#divContent').html(response.msg);
		    }
		});
	    });
	}
   
   });
   
     $('#btnIncludeAndPrint').live('click', function() {
	$('#divContent').html("");
	var pinlen  = jQuery.trim($('#pinno').val());
	if(pinlen.length<11)
	{
		alert("Please Enter Pinno with 11 characters.");
		$('#pinno').val(pinlen);
		$('#pinno').focus();
		return false;
	}
	var a = $("#tenantmasid option:selected").val();
        if((a == null) || (a==""))
	{
	    alert("Please select tenant");return false;
	}
	if($('#pinno').val() =="" )
	{
		alert("Please enter pinno");
		$('#pinno').focus();
		return false;
	}
	if($('#tenancyrefcode').val() =="" )
	{
		alert("Please enter tenancyrefcode");
		$('#tenancyrefcode').focus();
		return false;
	}
        var r=confirm("Can you confirm this?");
	if (r == true)
	{	    	    
	    var url="save_list.php?action=include";
	    var dataToBeSent = $("#form1").serialize();
	    $.getJSON(url,dataToBeSent, function(data){
		$.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {
			$('#divContent').html(response.divContent);
			////generate_tenancyrefcode(0);
			loadrunningtenant();
              var a = confirm("Would you like to print the possession letter?");
                if (a== true)
                {
                 var url="save_print_possessionletter.php?";
		
                var grouptenantmasid = $("#tenantmasid option:selected").val();
		window.open(url+"grouptenantmasid="+grouptenantmasid,  "windowOpenTab", "width=1800,height=800,scrollbars=yes,resizable=yes,toolbars:yes");
                return false;
                }else{
                    return;
                }
		    }	
		    else
		    {
			$('#divContent').html(response.msg);
		    }
		});
	    });
	}
   
   });
   $('#btnDelete').live('click', function() {
	$('#divContent').html("");
	var a = $("#tenantmasid option:selected").val();
       
        if((a == null) || (a==""))
	{
	    alert("Please select tenant");return false;
        }
        var r=confirm("Do you wish to delete this record, Note that this will delete all records for this tenant?");
	if (r == true)
	{	    	    
	    var url="load_list.php?item=delete&grouptenantmasid="+a;
            //alert(url);
	    var dataToBeSent = $("#form1").serialize();
	    $.getJSON(url,dataToBeSent, function(data){
		$.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {
			$('#divContent').html('Record deleted successfully from waiting list');
	                 alert('Record deleted successfully from waiting list');
		        loadwaitinglist(0);
	               // loadrunningtenant(0);
                    }	
		    else
		    {
			alert('Error deleting record!');
                        $('#divContent').html('Error deleting record!');
		    }
		});
	    });
	}
   
   });
   
//        $('#btnIncludeAndPrint').live('click', function() {
//	$('#divContent').html("");
//	var pinlen  = jQuery.trim($('#pinno').val());
//	if(pinlen.length<11)
//	{
//		alert("Please Enter Pinno with 11 characters.");
//		$('#pinno').val(pinlen);
//		$('#pinno').focus();
//		return false;
//	}
//	var a = $("#tenantmasid option:selected").val();
//        if((a == null) || (a==""))
//	{
//	    alert("Please select tenant");return false;
//	}
//	if($('#pinno').val() =="" )
//	{
//		alert("Please enter pinno");
//		$('#pinno').focus();
//		return false;
//	}
//	if($('#tenancyrefcode').val() =="" )
//	{
//		alert("Please enter tenancyrefcode");
//		$('#tenancyrefcode').focus();
//		return false;
//	}
//        var r=confirm("Can you confirm this?");
//	if (r == true)
//	{	    	    
//	    var url="save_list.php?action=include";
//	   var dataToBeSent = $("form1").serialize();
//             
//	    $.getJSON(url,dataToBeSent, function(data){
//		$.each(data.error, function(i,response){
//		    if(response.s == "Success")
//		    {
//			$('#divContent').html(response.divContent);
//			////generate_tenancyrefcode(0);
//			loadrunningtenant();
//			////loadwaitinglist();
//                        
//     	
//		    }	
//		    else
//		    {
//			$('#divContent').html(response.msg);
//		    }
//		});
//	    });
//	}
//   
//   });
    $('#btnExclude').click(function(){
	$('#divContent').html("");
	var a = $("#grouptenantmasid option:selected").val();
        
        if((a == null) || (a==""))
	{
	    alert("Please select tenant");return false;
	}
        var r=confirm("Can you confirm this?");
	if (r == true)
	{
	    var url="save_list.php?action=exclude";
	    var dataToBeSent = $("#form2").serialize();
	    $.getJSON(url,dataToBeSent, function(data){
		$.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {
			$('#divContent').html(response.divContent);
			loadrunningtenant();
			loadwaitinglist();
		    }	
		    else
		    {
			$('#divContent').html(response.msg);
		    }
		});
	    });
	}
    });
    $('#tenantmasid').change(function(){
         //alert($(this).val())
	$('#tenantdetails').html("");
	$('#divContent').html("");
	var a = $("#tenantmasid option:selected").val();
        
        if((a == null) || (a==""))
	{
	    alert("Please select tenant");return false;
	}		
//	var url="load_list.php?item=chkdoc&grouptenantmasid="+a;	    
//        $.getJSON(url,function(data){
//            $.each(data.error, function(i,response){
//                if(response.s == "Success")
//                {                    
//                    $.each(data.myResult, function(i,response){
//			var a = response.datediff;
//			var b = response.leasename;
//			var c = response.tradingname;
//			var doc =  response.doc;
//			var shop =  response.shopcode;
//			var size =  response.size;
//			var shopstatus ="";
//			var shopoccupied = response.shopoccupied;
//			var shopstatus =" Shop Free";
//			if(shopoccupied == 1)
//				shopstatus =" <font color='red' > Shop Occupied </font>";
//			if(c !="")
//				b += "<br> (T/A) <br>"+c;			
//				$('#tenantdetails').html("<font color='green'><b>"+b+"</b><br><br>("+doc+" , "+shop+" , "+size+", "+shopstatus+")<br><br></font>");
//				//$('#tenantdetails').append("To&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' id='email1' name='email1' value='suku@shiloahmega.com'><br><br>");
//				//$('#tenantdetails').append("Remarks:<input type='text' id='remarks1' name='remarks1' value='' width='80px'>" +
//							   //"<br><br><input type='button' id='btnchange' name='btnchange' value='Change DOC'><br>");
//				//$('#tenantdetails').append("<input type='hidden' id='det1' name='det1' value='"+b+","+doc+","+shop+"'><br>");			
//                    });
//                }
//                else
//                {
//			$('#tenantdetails').html(response.msg);
//                }
//            });		
//        });
	var url="load_list.php?item=chkpinno&grouptenantmasid="+a;	
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){		
                if(response.s == "Success")
                {                                        
		    $('#tenantdetails').html("<br><b>"+response.leasename+"</b><br>");
		    $('#tenantdetails').append("<br>"+response.shopcode+"<br>");
		    $('#tenantdetails').append("<br>&nbsp;&nbsp;PIN NO: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' id='pinno' name='pinno' value='"+response.pinno+"' style='width:100px;' maxlength='11'>");
		    $('#tenantdetails').append("<br><br>TENANCY CODE: &nbsp;<b>"+response.tenancyrefcode+"</b>");
			 var department = '<?php echo getUserDept(); ?>';
			 //alert(department);
			 if(department==1){
            $('#tenantdetails').append("<br><br><button type='button' id='btnDelete'>Delete</button>");
			 }
			 if(department==5 || department==2){
		    $('#tenantdetails').append("<br><br><button type='button' id='btnInclude'>Include</button>");
            $('#tenantdetails').append("<br><br><button type='button' id='btnIncludeAndPrint'>Include & Print Possession Letter</button>");
		     }
            
                 }
		else
		{
		    $('#cc').html(response.msg);
		}
	    });
	});
    });
   $('[id^="btnchange"]').live('click', function() {
	var a =  $('[id^="det1"]').val();	
	var r=confirm("Are you sure ? You are about to change DOC.");
	if (r == true)
	{
	    var url="save_list.php?action=changedoc&premisedetails="+a;	    
	    var dataToBeSent = $("#form1").serialize();
	    $.getJSON(url,dataToBeSent, function(data){
		$.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {
			$('#divContent').html("<font color='green'><b>"+response.divContent+"<b></font>");			
		    }	
		    else
		    {
			$('#divContent').html(response.msg);
		    }
		});
	    });
	}
    });
   $('[id^="btnraiseinvoice"]').live('click', function() {
	var a =  $('[id^="det2"]').val();	
	//alert(a);
	 var r=confirm("Are you sure ? You are about to raise Rent in Advance.");
	if (r == true)
	{
	    var url="save_list.php?action=raiseadvanceinvoice&premisedetails="+a;	    
	    var dataToBeSent = $("#form2").serialize();
	    $.getJSON(url,dataToBeSent, function(data){
		$.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {
			$('#divContent').html("<font color='green'><b>"+response.divContent+"<b></font>");			
		    }	
		    else
		    {
			$('#divContent').html(response.msg);
		    }
		});
	    });
	}
    });
    $('#grouptenantmasid').change(function(){	
	var a = $("#grouptenantmasid option:selected").val();	
        if((a == null) || (a==""))
	{
	    alert("Please select tenant");return false;
	}	
	var url="load_list.php?item=raiseinvoice&grouptenantmasid="+a;	
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {                    
                    $.each(data.myResult, function(i,response){
			var a = response.datediff;
			var b = response.leasename;
			var c = response.tradingname;
			var doc =  response.doc;
			var shop =  response.shopcode;
			var size =  response.size;
			var tenancyrefcode = response.tenancyrefcode;			
			
			if(c != '')
			b += "<br> (T/A) <br>"+c;
			
			$('#runningtenantdetails').html("<font color='green'><b>"+b+"</b><br><br>("+doc+" , "+shop+" , "+size+" , "+tenancyrefcode+")<br><br></font>");
			$('#runningtenantdetails').append("To&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' id='email2' name='email2' value='ronald-finance@shiloahmega.com'><br><br>");
			$('#runningtenantdetails').append("Remarks:<input type='text' id='remarks2' name='remarks2' value='' width='80px'>" +
						"<br><br><input type='button' id='btnraiseinvoice' name='btnraiseinvoice' value='Raise Advance Invoice'>");
			$('#runningtenantdetails').append("<input type='hidden' id='det2' name='det2' value='"+b+","+doc+","+shop+"'><br>");				
                    });
                }
                else
                {
			$('#runningtenantdetails').html(response.msg);
                }
            });		
        });
    });
    $(':checkbox').live('change', function() {
	var len = $("input:checkbox:checked").length;
	var buildingmasid="";
	$(':checkbox').each(function() {
		if($(this).is(':checked')) {	
			var id = $(this).attr('id')
			buildingmasid +=id+",";
		}
	});
	//$('#cc').html(buildingmasid);
	loadwaitinglist(buildingmasid);
	loadrunningtenant(buildingmasid);
    });
    
});
</script>
</head>
<body id="dt_example">
<!--<div id="container">-->
	<center><h1>Waiting List</h1></center>
<form id="form1" name="form1" action="#" method="post">
	<div id="divContent">
		
	</div>
</br>
<span id='cc'></span>
<table id="usertbl" class="table6" width="80%">
	<thead>
		<tr>
			<td id="tblheader" align="left" colspan="2">				
				<?php
					loadbuilding();
				?>
			</td>
		</tr>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Waiting For Approval:
			</th>
		</tr>
	</thead>
	<tbody>	 
	<tr>		
		<td width="50%">			
				<select id="tenantmasid" name="tenantmasid" style='width:525px;height: 250px;' size="15" class="selectbox">
					<option value="" selected>----Select Tenant----</option>
				</select>			
		</td>
                <td id='tenantdetails'>			
		</td>		
	</tr>	
	<tr>
		<td colspan="2" align="right">
			<!--<button type="button" id="btnInclude">Include</button>-->
		</td>
	</tr>
</table>
</form>
<form id="form2" name="form2" action="#" method="post">
<table id="usertbl" class="table6" width="80%">
    <thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Running Tenant:
			</th>
		</tr>
	</thead>
	<tr>		
		<td width="50%">	
			<select id="grouptenantmasid" name="grouptenantmasid" style='width:525px;height: 365px;' size="15">
				<option value="" selected>----Select Group Tenant----</option>
			</select>
		</td>
                <td id='runningtenantdetails'>			
		</td>
	</tr>	
	<tr>		
		<td colspan="2" align="right">
			<button type="button" id="btnExclude">Exclude</button>			
		</td>
	</tr>	
	
	</tbody>
</table>
</form>
  <center><div id="filedetails">
<div style='height:8px'></div>
<span class='span_cont'>Available Possession Letters:</span><div style='height:8px'></div>
<?php
$directory="../../pms_docs/possessionletters/";
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

</div></center> <!--File Details-->
          
<!--</div>-->
 <!--Main Div-->
</body>
</html>