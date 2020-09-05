<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
	<title>ENQUIRY MASTER REPORT</title>
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
    
    $firstDayUTS = mktime (0, 0, 0, date("m"), 1, date("Y"));
    $lastDayUTS = mktime (0, 0, 0, date("m"), date('t'), date("Y"));
    
    $start = date("d F Y", $firstDayUTS);
    $end = date("d F Y", $lastDayUTS);     
?>
<style>
    @import "../css/print.css";
    #sortable1, #sortable2 { list-style-type: none; margin: 0; padding: 0; zoom: 1; }
    #sortable1 li, #sortable2 li { margin: 0 5px 5px 5px; padding: 3px; width: 99%; }
    ul
    {
        list-style-type:none;
        padding:0px;
        margin:0px;
    }
    ul li
    {
        background-image:url(sqpurple.gif);
        background-repeat:no-repeat;
        background-position:0px 5px; 
        padding-left:14px;
    }
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    enquirydetails_cons();
    $(".date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd MM yy'
    });
    $('#buildingmasid')[0].focus()    
    function enquirydetails()
    {        
        var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
        var b = $('#buildingmasid').val();
        if(b!="")
        {
            $('#buildingDiv').empty();
            var url="load_enquiry.php?item=enquirydetails&buildingmasid ="+b;
            var dataToBeSent = $("form").serialize();
            $.getJSON(url,dataToBeSent, function(data){				
                $.each(data.error, function(i,response){
                    if(response.s == "Success")
                    {
                        $('#buildingDiv').append(response.divContent);
                    }
                });
            });                
        }            
    }
    function enquirydetails_cons()
    {                
        $('#buildingDiv').empty();
        var url="load_enquiry.php?item=enquirydetails_cons";
        var dataToBeSent = $("form").serialize();
        $.getJSON(url,dataToBeSent, function(data){				
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {
                    $('#buildingDiv').append(response.divContent);
                }
            });
        });                
    }
    $('[id^="btnViewCons"]').live('click', function() {
        enquirydetails_cons();
    });
    $('[id^="btnView"]').live('click', function() {
        if(jQuery.trim($("#buildingmasid").val()) == "")
        {
                alert("Please select building");
                $("#buildingmasid").focus();return false;
        }
        if(jQuery.trim($("#fromDt").val()) == "")
        {
                alert("Please enter from date");
                $("#fromDt").focus();return false;
        }
        if(jQuery.trim($("#toDt").val()) == "")
        {
                alert("Please enter to date");
                $("#toDt").focus();return false;
        }
        enquirydetails();
    });
    $('[id^="btnPrint"]').live('click', function() {
            $('.printable').print();
    });
    $("#btnExport").click(function(e) {
            window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#buildingDiv').html()));
            e.preventDefault();
    });
    function removecomma(val)
    {
        return String(val).replace(/\,/g, '');			    
    }
    function commafy(nStr) {
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
});
</script>
<style>
.intro
{
	font-size:150%;
	color:green;
}
</style>
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="#" method="post">
<p><font size="+1" color="#000066"> <b> ENQUIRY MASTER REPORT </b> </font> </p><hr color="#000066" height="2px"></br>
<table cellpadding="5" cellspacing="5">
         <tr>            
            <td align="right" colspan="2">
                <label for="from">Building</label>
                <select id="buildingmasid" name="buildingmasid" style='width: 225px;'>
                    <option value="" selected>----Select Building----</option>
                    <option value="0">All</option>
                    <?php loadBuilding();?>
                </select>
            </td>
        </tr>
        <tr>           
            <td colspan="2">
                <label for="from">From</label>
                <input type="text" id="fromDt" name="fromDt" class="date" style='width: 150px;' value='<?php  echo $start;?>' readonly/>
                <label for="to">to</label>
                <input type="text" id="toDt" name="toDt" class="date" style='width: 150px;' value='<?php  echo $end;?>' readonly/>                
            </td>
        </tr>
        <tr>
             <td colspan="2" align="right">
                <button type="button" id="btnViewCons">View Cons</button>
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
&nbsp;&nbsp;<font color=red><label id="st"></label></font>
</body>
</html>
