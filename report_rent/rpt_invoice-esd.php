<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Monthly Rental Invoice</title>
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
  /*   $companymasid = $_SESSION['mycompanymasid'];
	if($companymasid == '2') // GRANDWAYS VENTURES LTD
	    $sql = "select buildingmasid, buildingname from mas_building where companymasid ='$companymasid';";
	else
	    $sql = "select buildingmasid, buildingname from mas_building where companymasid !='2';"; */
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
<link href="style_progress.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	// Setup the ajax indicator    	
	$('#ajaxBusy').css({
		display:"none",
	//	margin:"0px",
	//	paddingLeft:"0px",
	//	paddingRight:"0px",
	//	paddingTop:"0px",
	//	paddingBottom:"0px",
	//	position:"absolute",
	//	right:"3px",
	//	top:"3px",
		width:"auto"
	});
	// Ajax activity indicator bound 
	// to ajax start/stop document events
	$(document).ajaxStart(function(){ 
		$('#ajaxBusy').show(); 
	}).ajaxStop(function(){ 
		$('#ajaxBusy').hide();
	});
    $("#invdt").datepicker({
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
		//invGen();
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
    $('#buildingmasid')[0].focus()
    
    $('#buildinglistTbl').hide();
    
    $('[id^="buildingmasid"]').live('change', function() {
        ////invGen();
		$('[id^="btnTally"]').prop('disabled', false);
    });
    $('[id^="btnSave"]').live('click', function() {
	if($("#buildingmasid").val() =="")
	    {
		alert("Building Selection is mandatory.");
		$("#buildingmasid").focus();
		return false;
	    }
	var r = confirm("can you confirm this?");
	if(r==true)
	{	    
	    //$(this).attr("disabled", true);
	    //$(this).hide();
	    invGen();	
	}
    });
	
	$('[id^="btnTally"]').live('click', function() {
	if($("#buildingmasid").val() =="")
	    {
		alert("Building Selection is mandatory.");
		$("#buildingmasid").focus();
		return false;
	    }
	var r = confirm("Do you wish to proceed to post to Tally invoices for "+$("select[id='buildingmasid'] option:selected").text()+" "+'<?php  echo $end;?>'+"?");
	if(r==true)
	{	    
	    //$(this).attr("disabled", true);
	    //$(this).hide();
	   invGenTally();	
	}
    });
    function invGen()
    {
        var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
        if($('#buildingmasid').val()!="")
        {
            $('#buildingDiv').empty();
	    var dataToBeSent = $("form").serialize();
	    window.open("load_invoice.php?"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
            return false;           
        }
        else
        {
            $('#buildinglistTbl').hide('slow');
        }    
    }
	  function invGenTally()
    {
        $('#btnTally').hide();
		alert('check the browser windows for transaction progress');
		
		var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
		
        if($('#buildingmasid').val()!="")
        {
            $('#buildingDiv').empty();
	    var dataToBeSent = $("form").serialize();
	    window.open("load_invoice_tally.php?"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
            return false;           
        }
        else
        {
            $('#buildinglistTbl').hide('slow');
        }   
    }
    //CHECK IF ANY ERRORS
    $('[id^="btnCheck"]').live('click', function() {
	if($("#buildingmasid").val() =="")
	    {
		alert("Building Selection is mandatory.");
		$("#buildingmasid").focus();
		return false;
	    }
	var r = confirm("can you confirm this CHECK?");
	if(r==true)
	{
	    invCheck();	
	}
    });
    function invCheck()
    {
        var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
        if($('#buildingmasid').val()!="")
        {
            $('#buildingDiv').empty();
	    var url="check_invoice.php";
            var dataToBeSent = $("form").serialize();
            $.getJSON(url,dataToBeSent, function(data){				
                $.each(data.error, function(i,response){
                    if(response.s == "Success")
                    {
                        $('#buildingDiv').append(response.divContent);            
                    }                    
                });
            });
            return false;           
        }
        else
        {
            $('#buildinglistTbl').hide('slow');
        }
    }    
    $('[id^="btnView"]').live('click', function() {
	if($("#buildingmasid").val() =="")
	    {
		alert("Building Selection is mandatory.");
		$("#buildingmasid").focus();
		return false;
	    }
	var r = confirm("can you confirm this?");
	if(r==true)
	{
	    var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
	    if($('#buildingmasid').val()!="")
	    {
		$('#buildingDiv').empty();
		var dataToBeSent = $("form").serialize();
		window.open("view_invoice.php?"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
		return false;           
	    }
	    else
	    {
		$('#buildinglistTbl').hide('slow');
	    }
////	    $('#buildingDiv').empty();
////            var url="view_invoice.php";
////            var dataToBeSent = $("form").serialize();
////            $.getJSON(url,dataToBeSent, function(data){				
////                $.each(data.error, function(i,response){
////                    if(response.s == "Success")
////                    {
////                        $('#buildingDiv').append(response.divContent);
////                        //$('#heading').html("<strong>"+response.heading+"<strong>");                    
////                    }
////                    $('#buildinglistTbl').show('slow');                                
////                    $('input:checkbox').attr('checked', true);
////                });
////            });
	    
	}
    });
});
    
</script>
<!--<link href="style_progress.css" rel="stylesheet" type="text/css" /> 
--></head>

<body id="dt_example" style="width: 90%">
<form id="myForm" name="myForm" action="" method="post" target="_blank">
    <br>
    <h1 align='CENTER'>Rental Invoice</h1>
    <br>
	<span></span>	
    <br>   
    <table>
	<tr>
	    <th colspan='2'>Generate Rental Invoice</th>
	</tr>
         <tr>
		<td>
			Inv Period<font color='red'>*</font>
		</td>
		<td>
			<input type='text' name='invdt' id='invdt' value='<?php  echo $end;?>' readonly/>
		</td>
	</tr>
        <tr>
            <td>Building <font color="red">*</font></td>
            <td>
                <select id="buildingmasid" name="buildingmasid" style='width: 225px;'>
                    <option value="" selected>----Select Building----</option>
                    <?php loadBuilding();?>
                </select>    
            </td>
        </tr>
       
        <tr>
            <td><div id="ajaxBusy">Working..</br><p><img src="../images/spinner.gif"></p></div></td>
            <td>
		    <button type="button" id="btnView">Draft Invoice</button>
		    &nbsp;&nbsp;&nbsp;
		    <button type="button" id="btnSave">Save Invoice</button>
		    <button type="button" id="btnTally">Post To Tally</button>
	    </td>		
	</tr>
    </table>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <font color=red><label id="cc"></label></font>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <p id='heading' align='center'></p>
    <div id='buildingDiv'>
    
    </div>
    <span class='span_cont'>Invoices:</span><div style='height:8px'></div>
<?php
$directory="../../pms_docs/invoices/";
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
   //echo "<span class='span_cont'><u>".$date."</u></span><div style='height:8px'></div>";
   echo "<table class='table6'>";
   echo "<tr>
		<th>S.No</th>
		<th>File</th>
		<th>Type</th>
		<th>Size</th>
		<th>Created On</th>
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
			<th>File</th>
			<th>Type</th>
			<th>Size</th>
			<th>Created On</th>
			</tr>";
		$w=1;
       }
       $link = $directory.$file;
       echo "<tr>";
       echo "<td>$w</td>";
       echo "<td><a href='$link' target='_blank'>$file</a></td>";
       echo "<td>$type</td>";
       echo "<td>".formatSizeUnits($size)."</td>";       
       echo "<td align='center'>$date</td>";
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
    &nbsp;&nbsp;
    <br>
</form>
</body>
</html>

