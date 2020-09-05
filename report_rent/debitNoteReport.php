<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Credit Note REPORT</title>
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
    
$start =  date("01-m-Y");    
 
     //1 MONTH FROM TODAY
    $end = date("d-m-Y",strtotime("-1 day"));
?>
<style>
    @import "../css/print.css";
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    $('#buildingmasid')[0].focus();
    $(".monthYear").datepicker({
            showOn: "button",
	    buttonImage: "../images/calendar.gif",
	    buttonImageOnly: true,
	    changeMonth: true,
	    changeYear: true,
	    dateFormat:"dd-mm-yy"
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
                            $('#crdtFrom').val(response.yst);
                            $('#crdtTo').val(response.yend);
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
            //var url="crdt_rpt.php?buildingmasid="+buildingmasid+";
            var dataToBeSent = $("form").serialize();
              // alert(dataToBeSent);
              $("#buildingDiv").load("dbt_rpt.php?"+dataToBeSent).fadeIn("slow");
  
        }
        else
        {
            $('#buildinglistTbl').hide('slow');
            alert("Please Select Building");
            $('#buildingmasid')[0].focus();
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
<p><font size="+1" color="#000066"> <b> DEBIT NOTE REPORT </b> </font> </p><hr color="#000066" height="2px"></br>
    <table cellpadding="5" cellspacing="5">
         <tr>            
            <td align="right" colspan="2">
                <label for="from">Building</label>
                <select id="buildingmasid" name="buildingmasid" style='width: 130px;'>
                    <option value="" selected>Select</option>
                    <?php loadBuilding();?>
                </select>
                <label for="from">From</label>
                <input type="text" id="dbtFrom" name="dbtFrom" class="monthYear" style='width: 120px;' value='<?php  echo $start;?>' readonly/>
                <label for="to">to</label>
                <input type="text" id="dbtTo" name="dbtTo" class="monthYear" style='width: 120px;' value='<?php  echo $end;?>' readonly/>
                <button type="button" id="btnView">View</button>
                 
            </td>
        </tr>        
    </table>        
  
</form>
      <div id='buildingDiv'>
    
    </div>
    &nbsp;&nbsp;<font color=red><label id="cc"></label></font>
<br>
</body>
</html>

