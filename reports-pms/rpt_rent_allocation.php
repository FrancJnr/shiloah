<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>RENT Allocation</title>
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
	    dateFormat:"dd-mm-yy",
	    showButtonPanel: true
    });    	
    $('#buildingmasid')[0].focus()
    $('[id^="btnrenttotal"]').live('click', function() {
        $('#buildingDiv').html('');
        renttotal();
    });
    
    $('[id^="btnrfp"]').live('click', function() {
         $('#buildingDiv').html('');
        rentfortheperiod();
    });
    
    $('[id^="btnrnfp"]').live('click', function() {
        $('#buildingDiv').html('');
        rentnotfortheperiod();
    });
    function renttotal()
    {        
	var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
        if($('#buildingmasid').val()!="")
        {            
            var url="load_rent_total.php";
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
    }
    function rentnotfortheperiod()
    {        
	var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
        if($('#buildingmasid').val()!="")
        {            
            var url="load_rent_not_for_the_period.php";
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
			    ////$('#cc').append(c);
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
                });
            });
                
        }        
    }
    function rentfortheperiod()
     {        
	var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
        if($('#buildingmasid').val()!="")
        {            
            var url="load_rent_for_the_period.php";
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
			    ////$('#cc').append(c);
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
                });
            });
                
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
    <p><font size="+1" color="#000066"><b>RENT ALLOCATION</b> </font> </p><hr color="#000066" height="2px"></br>
    
    From Date <font color='red'>*</font>
    <input type='text' name='fromdt' id='fromdt' class='datepick' value='' style='width: 120px;' readonly/>
    
    To Date <font color='red'>*</font>
    <input type='text' name='todt' id='todt' class='datepick' value='' style='width: 120px;' readonly/>
    
    Building <font color='red'>*</font>
    <select id="buildingmasid" name="buildingmasid" style='width: 150px;'>
    <option value="" selected>----Select Building----</option>
	<?php loadBuilding();?>
    </select>
    <button type="button" id="btnrenttotal">Total Invoice</button>
    <button type="button" id="btnrfp">For the Period</button>
    <button type="button" id="btnrnfp">NOT For the Period</button>
    <button type="button" id="btnPrint">Print</button>
    &nbsp;&nbsp;<font color=red><label id="cc"></label></font>
    <p id='heading' align='center'></p>
    <div id='buildingDiv'>
    
    </div>  
</form>
</body>
</html>
