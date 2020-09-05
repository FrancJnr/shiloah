<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Monthly Rental Schedule</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
   // include('ip_test.php');      
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
    //TODAY'S DATE
     $start =  date("F Y");
 
     //1 MONTH FROM TODAY
     $end = date("F Y",strtotime("+0 months"));
?>

<style type="text/css">
 input[type="text"]{
	border: 1px solid;
	background:#f8f8f8;
	width: 200px;
	height: 25px;
	font-size: 12pt;
	font-family:"Calibri";
}   
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	    //minDate:0,
	$("#fromdate").datepicker({
			showOn: "button",
			buttonImage: "../images/calendar.gif",
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat:"MM yy",
			showButtonPanel: true,
			onClose: function() {
			    var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			    var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			    $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
			},
			beforeShow: function() {
			if ((selDate = $(this).val()).length > 0) 
			{
			   iYear = selDate.substring(selDate.length - 4, selDate.length);
			   iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5), 
				    $(this).datepicker('option', 'monthNames'));
			   $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
			   $(this).datepicker('setDate', new Date(iYear, iMonth, 1));               
			}
		    }
	});
	$('#noofmonths').html(caclcmonth());
    loadTenantDraft("loadtenantforadvancerent");
    function loadTenantDraft(itemtype)
    {
	var url="../reports-pms/load_report.php?item="+itemtype;	
	$.getJSON(url,function(data){
	    $.each(data.error, function(i,response){
		if(response.s == "Success")
		{
		    $('#grouptenantmasid').empty();
		    $('#grouptenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
		    $.each(data.myResult, function(i,response){
			    var t = response.tradingname;
			    if(t !="")
				var b = response.tradingname;
			    else
				var b = response.leasename;
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
    $('#grouptenantmasid')[0].focus();
    $('[id^="grouptenantmasid"]').live('change', function() {		
	    $('#grouptenant').empty();
	    $('#heading').html("<center>DRAFT COPY")
	    $('#result').html("");
	    var str= $('#grouptenantmasid option:selected').text();		
	    var temp = new Array();
	    temp = str.split("-"); //split -
	    temp = temp[1].split(")"); //split ')'
	    temp[0]; // building shortname from lease name and tenant code
	    var url="../reports-pms/load_report.php?item=grouptenant&itemval="+$(this).val()+"&buildingshortname="+temp[0];	
	    $.getJSON(url,function(data){
		    $.each(data.error, function(i,response){
			    $.each(data.myResult, function(i,response){
				    $('#grouptenant').append(response.leasename+" <strong>("+response.shopcode+","+response.size+","+response.tenantcode+")<input type='hidden' name='tenantmasid"+response.tenantmasid+"' value='"+response.tenantmasid+"'><br><br>");					
			    });
		    });		
	    });		
	    
    });	
    $('[id^="btnSave"]').live('click', function() {
	if(jQuery.trim($("#months").val()) <=0 )
	{
	       alert("Please enter valid months");
	       $('#months')[0].focus()
	       return false;
	}
	if($("#grouptenantmasid option:selected").val()== "")
	{
		alert("Please select tenant");
		$('#grouptenantmasid')[0].focus()
		return false;
	}
	//if($("#invno").val()== "")
	//{
	//	alert("Invoice No is Mandatory.");
	//	$('#invno')[0].focus()
	//	return false;
	//}
	var r = confirm("Are you sure, You want to save this Invoice?");
	if(r==true)
	{			
		var dataToBeSent = $("form").serialize();
		window.open("load_advance_rent.php?"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
                return false;
	}	
    });
     $('[id^="btnEstimate"]').live('click', function() {
	if(jQuery.trim($("#months").val()) <=0 )
	{
	       alert("Please enter valid months");
	       $('#months')[0].focus()
	       return false;
	}
	if($("#grouptenantmasid option:selected").val()== "")
	{
		alert("Please select tenant");
		$('#grouptenantmasid')[0].focus()
		return false;
	}	
	//if($("#invno").val()== "")
	//{
	//	alert("Invoice No is Mandatory.");
	//	$('#invno')[0].focus()
	//	return false;
	//}
		var url="estimate_advance_rent.php";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
		    $.each(data.error, function(i,response){
			    if(response.s == "Success")
			    {
				$('#result').html(response.result);
				$('#heading').html("<center>DRAFT COPY");
				$('#heading').append(response.heading);
			    }
			    else
			    {
				$('#cc').html(response.result);
			    }
		    });
		});
     });
     $(".numbersOnly").keydown(function(event) {	
        // Allow: backspace, delete, tab, escape, and enter
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
	$('#noofmonths').html(caclcmonth());
    });
    $(".numbersOnly").blur(function(event) {
	$('#noofmonths').html(caclcmonth());
    });
     $("#fromdate").blur(function(event) {
	$('#noofmonths').html(caclcmonth());
    });
     function caclcmonth()
     {
	var months = new Array(12);
	months[0] = "January";
	months[1] = "February";
	months[2] = "March";
	months[3] = "April";
	months[4] = "May";
	months[5] = "June";
	months[6] = "July";
	months[7] = "August";
	months[8] = "September";
	months[9] = "October";
	months[10] = "November";
	months[11] = "December";
    
	var beginDate = new Date( $("#fromdate" ).val());
	//if(beginDate >1)
	//    var numofMonthtoAdd = $("#months" ).val()-1;
	//else
	    var numofMonthtoAdd = $("#months" ).val();
        var month = (parseInt(beginDate.getMonth()) + parseInt(numofMonthtoAdd ));        
        beginDate.setMonth(month);
	month_value = beginDate.getMonth();	
	year_value = beginDate.getFullYear();
	var rt = months[month_value]+' '+year_value;
	return rt;
     }
});
</script>
<!--<link href="style_progress.css" rel="stylesheet" type="text/css" /> -->
</head>

<body id="dt_example" style="width: 90%">
    <form id="myForm" name="myForm" action="" method="post">
    <br>
    <h1 align='CENTER'>ADVANCE RENT INVOICE</h1>
    <br><br>
    <table width='100%' class='table6'>
	<tr>
	    <th colspan='2'>Generate Advance Rent</th>
	</tr>
	<tr>
	    <td>Tenant <font color='red'>*</font></td>
	    <td>
		<select id="grouptenantmasid" name="grouptenantmasid" style='width:525px;'>
		    <option value="" selected>----Select Group Tenant----</option>
		</select>
		</br></br>
		<span class='span_cont' id='grouptenant'></span>
	    </td>
	</tr>
	<tr>
	    <td>From Date<font color='red'>*</font></td>
	    <td><input type='text' name='fromdate' id='fromdate' value='<?php  echo $end;?>' readonly/>
	    NO BACK DATED INVOICES ALLOWED
	    <!--can be opened-->
	    </td>
	</tr>
	<tr>
	    <td>Months<font color='red'>*</font></td>
	    <td>
		<input type='text' class="numbersOnly" name='months' id='months' value='1'/>
		<span id='noofmonths' style="color: red;"></span>
	    </td>
	</tr>
	<!--<tr>
	    <td>Invoice No<font color='red'>*</font></td>
	    <td><input type='text' name='invno' id='invno' class="numbersOnly" value=''/></td>
	</tr>-->
	<tr>
	    <td colspan='2' align='right'>
		<button type="button" id="btnEstimate">Estimate</button>
		<button type="button" id="btnSave">Save</button>
	    </td>
	</tr>
    </table>
    <span id='heading'></span>
    <div id='result'>
    
    </div>
    </br></br>
    <center><h4>Avilable Advance Invoices</h4></center>
    <div style='height:8px'></div>  
<center><button class="buttonNew" name ="sendadvanceforsign" id="sendadvanceforsign"> Sign to ESD </button></center>	
<?php
$directory="../../pms_docs/advance_invoices/";
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
		<th>ESD</th>
		<th>Select</th>
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
			<th>ESD</th>
			<th>Select</th>
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
       echo "<td></td>";
	   echo "<td></td>";
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

    </form>
    &nbsp;&nbsp;<font color=red><label id="cc"></label></font>
    <br>
</form>
</body>
</html>

