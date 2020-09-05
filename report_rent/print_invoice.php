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
    function advancerent()
    {
        $sql = "select advancerentmasid, invoiceno from advance_rent where invoiceno!='';";
        $result = mysql_query($sql);
        if($result != null)
        {
            while($row = mysql_fetch_assoc($result))
            {
                echo("<option value=".$row['advancerentmasid'].">".$row['invoiceno']."</option>");		
            }
        }
    }
    function rent()
    {
        $sql = "select invoicemasid, invoiceno from invoice where invoiceno!='';";
        $result = mysql_query($sql);
        if($result != null)
        {
            while($row = mysql_fetch_assoc($result))
            {
                echo("<option value=".$row['invoicemasid'].">".$row['invoiceno']."</option>");		
            }
        }
    }
    function manualinvoice()
    {
        $sql = "select invoicemanmasid, invoiceno from invoice_man_mas where invoiceno!='';";
        $result = mysql_query($sql);
        if($result != null)
        {
            while($row = mysql_fetch_assoc($result))
            {
                echo("<option value=".$row['invoicemanmasid'].">".$row['invoiceno']."</option>");		
            }
        }
    }
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
    
    $('[id^="advancerentmasid"]').hide();
    $('[id^="invoicemasid"]').hide();
    $('[id^="invoicemanmasid"]').show();
    
    $('[id^="btnPrint"]').live('click', function() {	
	if($("#grouptenantmasid option:selected").val()== "")
	{
		alert("Please select tenant");
		$('#grouptenantmasid')[0].focus()
		return false;
	}	
	var r = confirm("Are you sure, You want to save this Invoice?");
	if(r==true)
	{			
		var dataToBeSent = $("form").serialize();
		window.open("load_print_invoice.php?"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
                return false;
	}	
    });    
    $('[id^="invoption"]').live('click', function() {       
        var a = $('input[name=invoption]:checked').val();        
        if(a=="0")
        {
            $('[id^="advancerentmasid"]').show();
            $('[id^="invoicemasid"]').hide();
            $('[id^="invoicemanmasid"]').hide();
        }
        else if(a=="1")
        {
            $('[id^="advancerentmasid"]').hide();
            $('[id^="invoicemasid"]').show();
            $('[id^="invoicemanmasid"]').hide();
        }
        else if(a=="2")
        {
            $('[id^="advancerentmasid"]').hide();
            $('[id^="invoicemasid"]').hide();
            $('[id^="invoicemanmasid"]').show();
        }
    });
});
</script>
<link href="style_progress.css" rel="stylesheet" type="text/css" /> 
</head>
<body id="dt_example" style="width: 90%">
<form id="myForm" name="myForm" action="" method="post">
    <br>
    <h1 align='CENTER'>Print Invoice</h1>
    <br><br>
    <table width='100%'>
	<tr>
	    <th colspan='2'>Print Generated Invoice:</th>
	</tr>
        <tr>	    
	    <td colspan="2" align="center">
		<!--Advance Rent <input type="radio" id="invoption" name="invoption" value="0" /> |
                Rental Invoice <input type="radio" id="invoption" name="invoption" value="1" />  | -->
                Manual Invoice <input type="radio" id="invoption" name="invoption" value="2" checked/>                 
	    </td>
	</tr>
        <tr>	    
	    <td colspan="2" align="center">
		Show Period <input type="checkbox" id="dateoption" name="dateoption" value="0" checked/> 
	    </td>
	</tr>
	<tr>
	    <td>Print Invoice<font color='red'>*</font></td>
	    <td>
		<select id="advancerentmasid" name="advancerentmasid" style='width:525px;'>
		    <option value="" selected>----Select Advance Invoice No----</option>
                    <?php advancerent();?>
		</select>
                <select id="invoicemasid" name="invoicemasid" style='width:525px;' >
		    <option value="" selected>----Select Rental Invoice No----</option>
                    <?php rent();?>
		</select>
                <select id="invoicemanmasid" name="invoicemanmasid" style='width:525px;'>
		    <option value="" selected>----Select Manual Invoice No----</option>
                    <?php manualinvoice();?>
		</select>				
	    </td>
	</tr>        
	<tr>
	    <td colspan='2' align='center'>
		<button type="button" id="btnPrint">Print</button>		
	    </td>
	</tr>
    </table>
    <div id='grouptenant'></div>
<font color=red><label id="cc"></label></font>
</form>&nbsp;&nbsp;
</body>
</html>

