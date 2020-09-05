<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>TENANCY INFO</title>
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
    $('[id^="btnView"]').live('click', function() {
        $('#buildingDiv').html('');     
        var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
        var buildingmasid = $("#buildingmasid").val();
        if(buildingmasid !="")
        {
            $('#buildingDiv').empty();
            var url="load_tenant_info.php";
            var dataToBeSent = $("form").serialize();
            $.getJSON(url,dataToBeSent, function(data){				
                $.each(data.error, function(i,response){
                    if(response.s == "Success")
                    {
                        $('#buildingDiv').html(response.divContent);                        
                    }
                    $('#buildinglistTbl').show('slow');                                                    
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
<p><font size="+1" color="#000066"> <b> TENANCY INFO </b> </font> </p><hr color="#000066" height="2px"></br>
    <table cellpadding="5" cellspacing="5">        
        <tr>
            <td align="left" colspan="2">
                <label><b>Options:</b></label></br>                
                &nbsp<input type='checkbox' name='column1_leasename' value='leasename'>Leasename
                &nbsp<input type='checkbox' name='column1_tenancyrefcode' value='tenancyrefcode'>Tenancy Code
                &nbsp<input type='checkbox' name='column1_shopcode' value='shopcode'>Shop No
                &nbsp<input type='checkbox' name='column1_sqrft' value='sqrft'>Sqrft
                &nbsp<input type='checkbox' name='column1_doc' value='doc'>Doc
                &nbsp<input type='checkbox' name='column1_leaseperiod' value='leaseperiod'>Lease Period                
                &nbsp<input type='checkbox' name='column1_doe' value='doe'>Doe
                &nbsp<input type='checkbox' name='column1_doo' value='doo'>Doo
                &nbsp<input type='checkbox' name='column1_shoptype' value='shoptype'>Shop Type
                &nbsp<input type='checkbox' name='column1_orgtype' value='orgtype'>Organization Type
                &nbsp<input type='checkbox' name='column1_nob' value='nob'>Nature of business                
                &nbsp<input type='checkbox' name='column1_rentcycle' value='rentcycle'>Rent Cycle
                &nbsp<input type='checkbox' name='column1_creditperiod' value='creditperiod'>Credit Period
                &nbsp<input type='checkbox' name='column1_creditlimit' value='creditlimit'>Credit limit
                 </br>
                &nbsp<input type='checkbox' name='column1_latefeeinterest' value='latefeeinterest'>Interest               
                &nbsp<input type='checkbox' name='column1_pin' value='pin'>Pinno
                &nbsp<input type='checkbox' name='column1_regno' value='regno'>Regno
                &nbsp<input type='checkbox' name='column1_address' value='address'>Address
                &nbsp<input type='checkbox' name='column1_phoneno' value='phoneno'>Phoneno
                &nbsp<input type='checkbox' name='column1_contactpersondetails' value='contactpersondetails'>Contact Person Details
            </td>
        </tr>
        <tr>            
            <td align="left" colspan="2">
                <label for="from"><b>Building:</b></label>
                <select id="buildingmasid" name="buildingmasid" style='width: 130px;'>
                    <option value="" selected>Select</option>
                    <?php loadBuilding();?>
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

