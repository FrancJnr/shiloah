<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Monthly Service Charge Schedule</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
    function loadBuilding()
    {
        $sql = "select buildingmasid, buildingname from mas_building where buildingmasid !='6'"; // exclude katangi
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
    $start =  date("d/m/Y");
    $end =  date("d/m/Y");
     //1 MONTH FROM TODAY
     //$end = date("F Y",strtotime("+1 months"));
?>
<style type="text/css" media="screen">
@import "../css/print.css";	
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    $('.datepick').datepicker({
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
                scdeposit();
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
        scdeposit();
    });
    function scdeposit()
     {        
	$('#buildingDiv').html("");
	var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
        if($('#buildingmasid').val()!="")
        {            
            var url="load_sc_deposit.php";
            var dataToBeSent = $("form").serialize();
            $.getJSON(url,dataToBeSent, function(data){				
                $.each(data.error, function(i,response){
                    if(response.s == "Success")
                    {
                        $('#buildingDiv').html(response.divContent);
			var rowCount = $('#sctbl td').length;			
			var $c=0
			$(".rowth").each(function() {
			    $c++;			    
			});
			$i=0;
			for($i=0;$i<$c;$i++)
			{
			    var sum = 0;			    
			    var c = 'row'+$i;
			    $('.'+c).each(function() {
				var value = removecomma($(this).text());			    
				if(!isNaN(value) && value.length != 0) {
				    sum += parseFloat(value);
				}
			    });
			    var c = 'tot'+$i;	
			    $('#'+c).html("<b>"+commafy(sum)+"</b>");			    			    
			}
                    }
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
    <p><font size="+1" color="#000066"><b>SERVICE CHARGE DEPOSIT REPORT</b> </font> </p><hr color="#000066" height="2px"></br>
    
    From Date <font color='red'>*</font>
    <input type='text' name='fromdt' id='fromdt' class='datepick' value='' readonly/>
    
    To Date <font color='red'>*</font>
    <input type='text' name='todt' id='todt' class='datepick' value='' readonly/>
    
    Select Building <font color='red'>*</font>
    <select id="buildingmasid" name="buildingmasid" style='width: 225px;'>
    <option value="" selected>----Select Building----</option>
	<?php loadBuilding();?>
    </select>
    
    <button type="button" id="btnPrint">Print</button>
    <!--<div id="ajaxBusy">Working..<img src="../images/spinner.gif"></div>-->
    <p id='heading' align='center'></p>
    <div id='buildingDiv'>
    
    </div>  
</form>
&nbsp;&nbsp;<font color=red><label id="cc"></label></font>
</body>
</html>
