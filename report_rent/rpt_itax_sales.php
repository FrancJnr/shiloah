<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Monthly i-Tax Report</title>
<link rel="stylesheet" type="text/css" href="../styles.css">
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
   // include('ip_test.php');
//    function loadBuilding()
//    {
//        $companymasid = $_SESSION['mycompanymasid'];
//	if($companymasid == '2') // GRANDWAYS VENTURES LTD
//	    $sql = "select buildingmasid, buildingname from mas_building where companymasid ='$companymasid';";
//	else
//	    $sql = "select buildingmasid, buildingname from mas_building where companymasid !='2';";
//        $result = mysql_query($sql);
//        if($result != null)
//        {
//                while($row = mysql_fetch_assoc($result))
//                {
//                        echo("<option value=".$row['buildingmasid'].">".$row['buildingname']."</option>");		
//                }
//        }
//    }
    function loadBuilding()
	{
		$companymasid = $_SESSION['mycompanymasid'];
		$sql = "select buildingmasid, buildingname from mas_building where companymasid = '$companymasid' order by buildingname";
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
    //TODAY'S DATE
     $start =  date("F Y");
 
     //1 MONTH FROM TODAY
     $end = date("F Y",strtotime("+1 months"));
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
//    var buildingmasid="";
//      $(':checkbox').live('change', function() {
//	var len = $("input:checkbox:checked").length;
//	 var buildingmasid="";
//	$(':checkbox').each(function() {
//		if($(this).is(':checked')) {	
//			var id = $(this).attr('id')
//			buildingmasid +=id+",";
//		}
//	});
////	//$('#cc').html(buildingmasid);
////	loadwaitinglist(buildingmasid);
////	loadrunningtenant(buildingmasid);
//    });  
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
//	    var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
//	    if($('#buildingmasid').val()!="")
//	    {
		//$('#buildingDiv').empty();
                var buildingmasid="";
                $(':checkbox').each(function() {
		if($(this).is(':checked')) {	
			var id = $(this).attr('id')
			buildingmasid +=id+",";
		}
	       });
		var dataToBeSent = $("form").serialize();
		window.open("view_itax_sales_report.php?"+dataToBeSent+"&buildings="+buildingmasid,  "windowOpenTab", "width=1800,height=800,scrollbars=yes,resizable=yes,toolbars:yes");
		return false;           
//	    }
//	    else
//	    {
//		$('#buildinglistTbl').hide('slow');
//	    }

	}
    });
});
    
</script>

<style type="text/css">
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
</head>
<body id="dt_example" >  
<form id="myForm" name="myForm" action="" method="post" target="_blank" style="margin:auto !important;">
    <br>
    <center><h1>Sales Report</h1></center>
    <br>
	<span></span>	
    <br>   
    <table id="usertbl" class="table6" width="80%">
	<thead>
		<tr>
			<td id="tblheader" align="center" colspan="2">				
				<?php
					loadBuilding();
				?>
			</td>
		</tr>
		  <tr>
	               <th colspan='2'>Generate i-Tax Sales</th>
                  </tr>
	</thead>
	<tbody>	
        
      
         <tr>
		<td>
			Inv Period<font color='red'>*</font>
		</td>
		<td>
			<input type='text' name='invdt' id='invdt' value='<?php  echo $end;?>' readonly/>
		</td>
	</tr>

        <tr>
            <td><div id="ajaxBusyf"></br><p></p></div></td>
            <td>
		    <button type="button" id="btnView">View Sales Report</button>
	    </td>		
	</tr>
        </tbody>
    </table>
    <font color=red><label id="cc"></label></font>
    </form>
     
</body>
</html>

