<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Interest Charges</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
  //  include('ip_test.php');      
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
    $('[id^="btnSave"]').live('click', function() {
	$('#cc').html("");	
	var r = confirm("Are you sure, You want to Import ASC?");
        if(r==true)
        {
            itemimport();
        }	
    });
   
   
     $('[id^="ToTally"]').live('click', function() {
	$('#cc').html("");	
	var r = confirm("Are you sure, You want to Import ASC To Tally?");
        if(r==true)
        {
            itemimporttotally();
        }	
    });
   
    function itemimport()
    {
        $('#cc').html("");			
        var url="import_interest_chrgs.php";	
        $("#form2").ajaxSubmit({
            url:url,	    
            data:$(this).serialize(),
            datatype:"json",
	    async: false,
            beforeSubmit: function() {
                //$('#cc').html("***");	
 $('[id^="btnSave"]').hide();				
            },
            success: function(msg) {
		if (msg != "") {     
		    $('#cc').html(msg);	
alert("Transaction Completed");		
 $('[id^="btnSave"]').show();	
		}
            }
        });	
	
    }
	
	
	  function itemimporttotally()
    {
        $('#cc').html("");			
        var url="import_interest_chrgs_tally.php";	
        $("#form2").ajaxSubmit({
            url:url,	    
            data:$(this).serialize(),
            datatype:"json",
	    async: false,
            beforeSubmit: function() {
                //$('#cc').html("***");		
			$('[id^="ToTally"]').hide();	
            },
            success: function(msg) {
		if (msg != "") {     
		    $('#cc').html(msg);		
alert("Transaction Completed");		
$('[id^="ToTally"]').show();	
		}
            }
        });	
	
    }
});
</script>
<!--<link href="style_progress.css" rel="stylesheet" type="text/css" /> -->
</head>

<body id="dt_example" style="width: 90%">
    <form id="form2" name="form2" enctype="multipart/formdata" action="" method="post" target="_blank">
    <br>
    <h1 align='CENTER'>INTEREST INVOICE</h1>
    <br><br>
    <table width='100%' class='table6'>
	<tr>
	    <th colspan='2'>Generate Invoice</th>
	</tr>
	<tr>
	    <td>Browse for excel file <font color='red'>*</font></td>
	    <td id=''>
		<input type='file' name='excelfile' id='excelfile' />   
	    </td>
	</tr>	
	<tr>
	    <td colspan='2' align='right'>		
		<button type="button" id="btnSave">Save</button>
	    </td>
	</tr>
    </table>
	
	<table width='100%' class='table6'>
	<tr>
	    <th colspan='2'>Post To Tally</th>
	</tr>
	<tr>
	    <td>Browse for excel file <font color='red'>*</font></td>
	    <td id=''>
		<input type='file' name='excelfile' id='excelfile' />   
	    </td>
	</tr>	
	<tr>
	    <td colspan='2' align='right'>		
		<button type="button" id="ToTally">Post To Tally</button>
	    </td>
	</tr>
    </table>
    <span id='heading'></span>
    <div id='result'>
    
    </div>
    </br>
	<span class='span_cont'>Avilable Interest Invoices:</span><div style='height:8px'></div>
<?php
$directory="../../pms_docs/interest_invoices/";
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
   echo "&nbsp;&nbsp;<font color=red><label id='cc'></label></font><br>";   
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
    </form>
   
    <br>
</form>
</body>
</html>

