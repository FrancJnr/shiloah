<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<titleTenancy Expiry List</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');    
    //TODAY'S DATE
    $start =  date("01-01-Y");    
 
     //1 YEAR FROM TODAY
    $end = date("01-01-Y",strtotime("+1 YEAR"));    
?>

<style>
    @import "../css/print.css";	
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {     
    $('.dtclass').datepicker({
            showOn: "button",
	    buttonImage: "../images/calendar.gif",
	    buttonImageOnly: true,
	    changeMonth: true,
	    changeYear: true,
	    dateFormat:"dd-mm-yy"
    });   
    //$('.datepick').datepicker({ dateFormat: 'dd-mm-yy'}).datepicker("setDate", new Date());
    //scdeposit();
     $('[id^="btnView"]').live('click', function() {
        scdeposit();
    });
    function scdeposit()
     {        
	$('#buildingDiv').html('');	
        var url="load_expiry_list.php";
        var dataToBeSent = $("form").serialize();
        $.getJSON(url,dataToBeSent, function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {
                    $('#buildingDiv').html(response.divContent);						
                }
            });
        });
     }
     $('[id^="btnPrint"]').live('click', function() {
        $('.printable').print();
    });
});
</script>
</head>
<body id="dt_example">    
    <form id="myForm" name="myForm" action="" method="post">
    <p><font size="+1" color="#000066"><b>TENANCY EXPIRY LIST</b> </font> </p><hr color="#000066" height="2px"></br>
    
    Select Date <font color='red'>*</font>
    <input type='text' name='fromdt' id='fromdt' class ='dtclass' value='<?php  echo $start;?>' readonly style='width: 150px;'/>
    
    To Date <font color='red'>*</font>
    <input type='text' name='todt' id='todt' class ='dtclass' value='<?php  echo $end;?>' readonly style='width: 150px;'/>    
    <button type="button" id="btnView">View</button>
    <button type="button" id="btnPrint">Print</button>
    &nbsp;&nbsp;<font color=red><label id="cc"></label></font>
    <p id='heading' align='center'></p>
    <div id='buildingDiv'>
    
    </div>  
</form>
</body>
</html>
