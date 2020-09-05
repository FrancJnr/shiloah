<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Lease Status Report</title>
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
        //$companymasid = $_SESSION['mycompanymasid'];
//	if($companymasid == '2') // GRANDWAYS VENTURES LTD
	   // $sql = "select buildingmasid, buildingname from mas_building where companymasid ='$companymasid';";
//	else
//	    $sql = "select buildingmasid, buildingname from mas_building where companymasid !='2';";
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
     //$start =  date("F Y");
 
     //1 MONTH FROM TODAY
     //$end = date("F Y",strtotime("+1 months"));
?>
<link href="style_progress.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	// Setup the ajax indicator    	
//	$('#ajaxBusy').css({
//		display:"none",
//	//	margin:"0px",
//	//	paddingLeft:"0px",
//	//	paddingRight:"0px",
//	//	paddingTop:"0px",
//	//	paddingBottom:"0px",
//	//	position:"absolute",
//	//	right:"3px",
//	//	top:"3px",
//		width:"auto"
//	});
//	// Ajax activity indicator bound 
//	// to ajax start/stop document events
//	$(document).ajaxStart(function(){ 
//		$('#ajaxBusy').show(); 
//	}).ajaxStop(function(){ 
//		$('#ajaxBusy').hide();
//	});
//    $("#invdt").datepicker({
//            showOn: "button",
//	    buttonImage: "../images/calendar.gif",
//	    buttonImageOnly: true,
//	    changeMonth: true,
//	    changeYear: true,
//	    dateFormat:"MM yy",
//	    showButtonPanel: true,
//	    onClose: function() {
//		var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
//		var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
//		$(this).datepicker('setDate', new Date(iYear, iMonth, 1));
//		//invGen();
//	    },
//	    beforeShow: function() {
//		if ((selDate = $(this).val()).length > 0) 
//		{
//		   iYear = selDate.substring(selDate.length - 4, selDate.length);
//		   iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5), 
//		   $(this).datepicker('option', 'monthNames'));
//		   $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
//		   $(this).datepicker('setDate', new Date(iYear, iMonth, 1));               
//		}
//	    }
//    });
    $('#buildingmasid')[0].focus()
    
    $('#buildinglistTbl').hide();
    
//    $('[id^="buildingmasid"]').live('change', function() {
//        ////invGen();
//    });
//    $('[id^="btnSave"]').live('click', function() {
//	if($("#buildingmasid").val() =="")
//	    {
//		alert("Building Selection is mandatory.");
//		$("#buildingmasid").focus();
//		return false;
//	    }
//	var r = confirm("can you confirm this?");
//	if(r==true)
//	{	    
//	    //$(this).attr("disabled", true);
//	    //$(this).hide();
//	    invGen();	
//	}
//    });
//    function invGen()
//    {
//        var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
//        if($('#buildingmasid').val()!="")
//        {
//            $('#buildingDiv').empty();
//	    var dataToBeSent = $("form").serialize();
//	    window.open("load_invoice.php?"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
//            return false;           
//        }
//        else
//        {
//            $('#buildinglistTbl').hide('slow');
//        }    
//    }
    //CHECK IF ANY ERRORS
//    $('[id^="btnCheck"]').live('click', function() {
//	if($("#buildingmasid").val() =="")
//	    {
//		alert("Building Selection is mandatory.");
//		$("#buildingmasid").focus();
//		return false;
//	    }
//	var r = confirm("can you confirm this CHECK?");
//	if(r==true)
//	{
//	    invCheck();	
//	}
//    });
//    function invCheck()
//    {
//        var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
//        if($('#buildingmasid').val()!="")
//        {
//            $('#buildingDiv').empty();
//	    var url="check_invoice.php";
//            var dataToBeSent = $("form").serialize();
//            $.getJSON(url,dataToBeSent, function(data){				
//                $.each(data.error, function(i,response){
//                    if(response.s == "Success")
//                    {
//                        $('#buildingDiv').append(response.divContent);            
//                    }                    
//                });
//            });
//            return false;           
//        }
//        else
//        {
//            $('#buildinglistTbl').hide('slow');
//        }
//    }    
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
		window.open("view_lease_report.php?"+dataToBeSent,  "windowOpenTab", "width=1800,height=800,scrollbars=yes,resizable=yes,toolbars:yes");
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

<body id="dt_example" width="100%" align="center">
<form id="myForm" name="myForm" action="" method="post" target="_blank" width="100%">
    <br>
    <center><h1 align='CENTER'>Lease Report</h1></center>
    <br>
	<span></span>	
    <br>   
    <table width="100%" align="center">
	<tr>
	    <th colspan='2'>Generate Lease Report</th>
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
            <td><div id="ajaxBusyf"></br><p></p></div></td>
            <td>
		    <button type="button" id="btnView">View Lease Report</button>
		    <!--&nbsp;&nbsp;&nbsp;-->
		    <!--<button type="button" id="btnSave">Save Invoice</button>-->
		    <!--<button type="button" id="btnCheck">Check Invoice</button>-->
	    </td>		
	</tr>
    </table>
    <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
    <font color=red><label id="cc"></label></font>
    <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
    <!--<p id='heading' align='center'></p>-->
    <!--<div id='buildingDiv'>-->
    
    <!--</div>-->
    <!--<span class='span_cont'>Invoices:</span><div style='height:8px'></div>-->

<!--</div> File Details-->
    </form>
    &nbsp;&nbsp;
    <br>
</form>
</body>
</html>

