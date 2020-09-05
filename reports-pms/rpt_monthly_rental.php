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
     $end = date("F Y",strtotime("+1 months"));
?>
<style type="text/css" media="screen">
@import "../css/Site.css";
@import "../css/TableTools.css";
table { margin: 1em; border-collapse: collapse;font-family: arial;font-size: 12px;}
td, th { padding: .3em; border: 1px #000000 solid; }
thead { background: #fc9; }
}
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$("#invdt").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'MM yy',
            maxDate: '+12m',
        onClose: function() {
            var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
            invGen();
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
    $('#buildingmasid')[0].focus()
    $('#buildinglistTbl').hide();    
     $('[id^="buildingmasid"]').live('change', function() {
        invGen();
    });
     function invGen()
     {
            var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
        if($('#buildingmasid').val()!="")
        {
            $('#buildingDiv').empty();
            var url="load_monthly_rental.php";
            var dataToBeSent = $("form").serialize();
            $.getJSON(url,dataToBeSent, function(data){				
                $.each(data.error, function(i,response){
                    if(response.s == "Success")
                    {
                        $('#buildingDiv').append(response.divContent);
                        $('#heading').html("<strong>"+response.heading+"<strong>");                    
                    }
                    $('#buildinglistTbl').show('slow');                                
                    $('input:checkbox').attr('checked', true);
                });
            });
                
        }
        else
        {
            $('#buildinglistTbl').hide('slow');
        }
     }
     $('[id^="btnPrint"]').live('click', function() {
            $('.printable').print();
    });
});
</script>
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
    <br>
    <h1 align='CENTER'>Monthly Rental Schedule</h1>
    <br>
    <table width='50%'>
	    <tr><td>
    <table>
         <tr>
		<td>
			Inv Period<font color='red'>*</font>
		</td>
		<td>
			<input type='text' name='invdt' id='invdt' value='<?php  echo $end;?>' readonly/>
		</td>
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
            <td></td>
            <td><button type="button" id="btnPrint">Print</button></td>
        </tr>
    </table>
	</td><td valign='top'>
    <table>
         <tr>
		<td>
			<img src='../images/yellow.jpg' height='35' width='35'/>
		</td>
		<td>
			Combined Shops
		</td>
		<td>
			<img src='../images/green.jpg' height='35' width='35'/>
		</td>
		<td>
			Tenure Yet to start
		</td>
	</tr>
        <tr>
            <td>
			<img src='../images/grey.jpg' height='35' width='35'/>
		</td>
		<td>
			Tenure Changed
		</td>
        </tr>
       
        <tr>
            <td>
			<img src='../images/red.jpg' height='35' width='35'/>
		</td>
		<td>
			Expired 
		</td>
        </tr>
    </table>
    
    </td></tr>
    </table>
    <p id='heading' align='center'></p>
    <div id='buildingDiv'>
    
    </div>
    </form>
    &nbsp;&nbsp;<font color=red><label id="cc"></label></font>
    <br>
</form>
</body>
</html>

