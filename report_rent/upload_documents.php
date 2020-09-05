<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Tenancy Documents</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
    $companymasid=$_SESSION['mycompanymasid'];
    
   // include('ip_test.php');      
     function loadBuilding($companymasid)
    {
        $sql = "select buildingname, buildingmasid from mas_building where companymasid=$companymasid order by buildingname asc";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['buildingmasid'].">".$row['buildingname']."</option>");		
                }
        }
    }
//         Loading from Enquiry List
    
          function loadTenantList($companymasid)
    {
        $sql = "select DISTINCT tenantmasid,  leasename  from mas_tenant where companymasid=$companymasid order by leasename asc";
//          $sql = "select companyname, enquirymasid from mas_enquiry_updated order by companyname desc";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['tenantmasid'].">".$row['leasename']."</option>");		
                
                        
                        
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
    
 
     $('[id^="upload"]').live('click', function() {
      //  alert('sasa');
      if(jQuery.trim($("#tenantmasid option:selected").val()) == "")
		{
			alert("Please select the tenant");
                        $("#tenantmasid").focus();
                        return false;
		}
		if($("#docname").val()== "")
		{
			alert("Please enter the document name");
                         $("#docname").focus();
                        return false;
		}
              
       var r = confirm("Are you sure, You want to Upload Tenant files?");
        if(r==true)
        {
        itemupload();    

        }
    function itemupload()
    {
       		
        var url="upload.php";	
        $("#form2").ajaxSubmit({
            url:url,	    
            data:$(this).serialize(),
            datatype:"json",
	    async: false,
            beforeSubmit: function() {
                //$('#cc').html("***");				
            },
            success: function(msg) {
		if (msg != "") {     
		    alert(msg);		    
		}
            }
        });	
		//var dataToBeSent = $("form").serialize();
		//window.open("import_asc.php"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
		//return false;   
    }
        
       

});

});
</script>
<!--<link href="style_progress.css" rel="stylesheet" type="text/css" /> -->
</head>

<body id="dt_example" style="width: 90%">
    <form id="form2" name="form2" enctype="multipart/formdata" action="" method="post">
    <br>
    <h1 align='CENTER'>TENANCY DOCUMENTS</h1>
    <br><br>
    <table width='100%' class='table6'>
	<tr>
	    <th colspan='2'>Upload Tenant Document</th>
	</tr>
      
         <tr>
        <td>
                Select Tenant <font color="red">*</font>
        </td>
        <td>
            <select id="tenantmasid" name="tenantmasid">
                    <option value="" selected>----Select Tenant----</option>
                  <?php loadTenantList($companymasid); ?>
            </select>
        </td>
     </tr>
       <tr>
        <td>
                Document Name <font color="red">*</font>
        </td>
        <td>
            <input id="docname" type="text" name="docname" placeholder="Enter Document Name" />   
        </td>
     </tr>

	<tr>
	    <td>Browse for file <font color='red'>*</font></td>
	    <td>
		<input id="file" type="file" name="file" />   
	    </td>
	</tr>	
	<tr>
	    <td colspan='2' align='center'>		
		<center><button id="upload">upload</button></center>
	    </td>
	</tr>
        
      
    </table>
     </form>
    <span id='heading'></span>
    <div id='result'>
    
    </div>
    </br>
	<span class='span_cont'>Available Tenancy Documents:</span><div style='height:8px'></div>
<?php
$directory="../../pms_docs/tenantdocs/";
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
 
    &nbsp;&nbsp;<font color=red><label id="cc"></label></font>
    <br>
</form>
</body>
</html>

