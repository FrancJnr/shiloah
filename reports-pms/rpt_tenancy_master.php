<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>TENANCY MASTER REPORT</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
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
    
    $sql = "select date_format(acyearfrom,'%M %y') as acyearfrom ,date_format(acyearto,'%M %y')
            as acyearto from mas_acyear where companymasid ='1' and active='1'; ";
    $start =  date("F Y");
 
     //1 MONTH FROM TODAY
    $end = date("F Y",strtotime("+0 months"));
?>
<style>
    @import "../css/print.css";
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    $('#buildingmasid')[0].focus();
    $(".monthYear").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd MM yy'
    });
    $('[id^="buildingmasid"]').live('change', function() {        
        var buildingmasid = $("#buildingmasid").val();
        if(buildingmasid !="")
        {
            $('#buildingDiv').empty();
            var url="load_report.php?item=acyear&itemval="+buildingmasid;
            var dataToBeSent = $("form").serialize();
            $.getJSON(url,dataToBeSent, function(data){				
                $.each(data.error, function(i,response){                    
                    if(response.s == "Success")
                    {                        
                        $.each(data.myResult, function(i,response){
                            $('#acyearFrom').val(response.yst);
                            $('#acyearTo').val(response.yend);
			});
                    }
                    else
                    {
                        $("#cc").html(response.s);
                    }
                });
            });
                
        }
        else
        {
            $('#buildinglistTbl').hide('slow');
            alert("Please Select Building");
            $('#buildingmasid')[0].focus()
        }
    });
    $('[id^="btnView"]').live('click', function() {
        var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
        var buildingmasid = $("#buildingmasid").val();
        if(buildingmasid !="")
        {
            $('#buildingDiv').empty();
            var url="save_tenancy_master.php";
            var dataToBeSent = $("form").serialize();
            $.getJSON(url,dataToBeSent, function(data){				
                $.each(data.error, function(i,response){
                    if(response.s == "Success")
                    {
                        $('#buildingDiv').append(response.divContent);                        
                    }
                    $('#buildinglistTbl').show('slow');                                
                    $('input:checkbox').attr('checked', true);
                });
            });
                
        }
        else
        {
            $('#buildinglistTbl').hide('slow');
            alert("Please Select Building");
            $('#buildingmasid')[0].focus()
        }
    });
   $('[id^="btnPrint"]').live('click', function() {
            $('.printable').print();
    });
    $("#btnExport").click(function(e) {
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#buildingDiv').html()));
        e.preventDefault();
    });
});
</script>
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<p><font size="+1" color="#000066"> <b> TENANCY MASTER REPORT </b> </font> </p><hr color="#000066" height="2px"></br>
    <table cellpadding="5" cellspacing="5">
         <tr>            
            <td align="right" colspan="2">
                <label for="from">Building</label>
                <select id="buildingmasid" name="buildingmasid" style='width: 130px;'>
                    <option value="" selected>Select</option>
                    <?php loadBuilding();?>
                </select>
                <label for="from">From</label>
                <input type="text" id="acyearFrom" name="acyearFrom" class="monthYear" style='width: 120px;' readonly/>
                <label for="to">to</label>
                <input type="text" id="acyearTo" name="acyearTo" class="monthYear" style='width: 120px;' readonly/>
                <select id="active" name="active" style='width: 130px;'>
                    <option value='0' selected>Active Tenancy</option>
                    <option value='1'>Full Tenancy</option>
                </select>
                <select id="renttype" name="renttype" style='width: 130px;'>
                    <option value='0' selected>Actual Rent</option>
                    <option value='1'>Monthly Rent</option>
                </select>
                <select id="rpttype" name="rpttype" style='width: 130px;'>
                    <option value='0' selected>Actual Report</option>
                    <option value='1'>Projections</option>
                </select>
                <button type="button" id="btnView">View</button>
                 <button type="button" id="btnPrint">Print</button>
                <button type="button" id="btnExport">Export</button>
            </td>
        </tr>        
    </table>        
    <div id='buildingDiv'>
    
    </div>
</form>
    &nbsp;&nbsp;<font color=red><label id="cc"></label></font>
<br>
</body>
</html>

