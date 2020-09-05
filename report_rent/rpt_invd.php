<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Daily Invoice Report</title>
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
    //function loadinvoicedesc()
    //{
    //    $sql = "select invoicedescmasid, invoicedesc from invoice_desc where active='1' order by invoicedesc";
    //    $result = mysql_query($sql);
    //    if($result != null)
    //    {
    //            while($row = mysql_fetch_assoc($result))
    //            {
    //                    echo("<option value=".$row['invoicedescmasid'].">".$row['invoicedesc']."</option>");		
    //            }
    //    }
    //}
    function loadinvoicedesc()
    {
        $sql = "select invoicedescmasid, invoicedesc from invoice_desc where active='1' order by invoicedesc";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
            $invoicedescmasid ="invoicedescmasid_".$row['invoicedescmasid'];
			$val = $row['invoicedescmasid'];			
			echo("<label><input type='checkbox' name='$invoicedescmasid' value='$val' />".$row['invoicedesc']."</label>");		
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
     $("#myTable").tablesorter(); 
    $('.dtclass').datepicker({
            showOn: "button",
	    buttonImage: "../images/calendar.gif",
	    buttonImageOnly: true,
	    changeMonth: true,
	    changeYear: true,
	    dateFormat:"dd-mm-yy"
    });   
    $('#buildinglistTbl').hide();
    
    $('[id^="btnPrint"]').live('click', function() {
		$('.printable').print();
	});
    $('[id^="btnList"]').live('click', function() {
	$('#cc').html("");
	$('#result_div').empty();
	var url="load_invd_list.php";
	var dataToBeSent = $("form").serialize();
	$.getJSON(url,dataToBeSent, function(data){
	    $.each(data.error, function(i,response){
		if(response.s == "Success")
		{
		    $('#result_div').html(response.result);
		}
	    });
	});
    });
    $('[id^="btnKiosk"]').live('click', function() {
	$('#cc').html("");
	$('#result_div').empty();
	var url="load_invd_kiosk.php";
	var dataToBeSent = $("form").serialize();
	$.getJSON(url,dataToBeSent, function(data){
	    $.each(data.error, function(i,response){
		if(response.s == "Success")
		{
		    $('#result_div').html(response.result);
		}
	    });
	});
    });
//    $('[id^="buildingmasid"]').live('change', function() {	
//	var url="load_invd_tenant.php?item=loadtenant&buildingmasid="+$(this).val();	
//	$.getJSON(url,function(data){
//	    $.each(data.error, function(i,response){		
//		if(response.s == "Success")
//		{		    		    		   
//		    $('#grouptenantmasid').empty();
//		    $('#grouptenantmasid').append( new Option("Tenant","",true,false) );		
//		    $.each(data.myResult, function(i,response){
//			var leasename = response.leasename;
//			var tradingname = response.tradingname;
//			
//			if(tradingname !="")
//			leasename +=" T/A "+tradingname;			
//			
//			var shopcode = response.shopcode
//			var sqrft = response.size;
//			leasename +=" ("+shopcode+"-"+sqrft+")";
//			
//			$('#grouptenantmasid').append( new Option(leasename,response.grouptenantmasid,true,false) );
//		    });
//		}
//		else
//		{
//		    $('#cc').html(response.s);
//		}
//	    });		
//	});
//    });
    $('[id^="btnToggle1"]').live('click', function() {
	if($(this).val()=='show')
	    $(this).val('hide');
	else
	    $(this).val('show');
	$('.rows1').toggle();
    });
    $('[id^="btnToggle2"]').live('click', function() {
	if($(this).val()=='show')
	    $(this).val('hide');
	else
	    $(this).val('show');
	$('.rows2').toggle();
    });
    $('[id^="btnToggle3"]').live('click', function() {
	if($(this).val()=='show')
	    $(this).val('hide');
	else
	    $(this).val('show');
	$('.rows3').toggle();
    });
    
    $('[id^="btnTotSales"]').live('click', function() {
	
	//var r = confirm("can you confirm this?");
	//if(r==true)
	//{
	    var url="load_invd.php";
	    var dataToBeSent = $("form").serialize();
	    $.getJSON(url,dataToBeSent, function(data){
		$.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {			
			$('#result_div').empty();						
			$('#result_div').html(response.result);
			var $tblcnt=0;
			$(".table6").each(function() {
			    $tblcnt++;			    
			});			
			$i=0;$j=0;
			var totalsales = 0;			    
			for($i=1;$i<=$tblcnt;$i++)
			{			    
			    var $clcnt=0
			    var col = 'cols'+$i;			    
			    $('.'+col).each(function() {
				$clcnt++;				
			    });
			    for($j=0;$j<$clcnt;$j++)
			    {
				var sum = 0;			    
				var c = 'row'+$i+$j;			    
				$('.'+c).each(function() {				
				    var value = removecomma($(this).text());				
				    if(!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
					//$('#cc').html(value);
				    }				    				    
				});
				var tot = 'tot'+$i+$j;		
				$('#'+tot).html("<font size='2'  color='#990000'>"+commafy(sum)+"</font>");
			    }			   
			}
			// group total sales
			var totsale ="<p class='printable'> <table class='table6' width='100%'>";
			totsale +="<th colspan=20>Total Sales: </th>";
			var $t0=0;var $t1=0;var $t2=0;
			for($i=1;$i<=$tblcnt;$i++)
			{
			    var $clcnt=0			    
			    var col = 'cols'+$i;		    
			    $('.'+col).each(function() {
				$clcnt++;				
			    });			    
			    $('#cc').html("");
			    $clcnt = $clcnt-1;
			    //totsale +="<tr>";    
			    for($j=0;$j<$clcnt;$j++)
			    {				
				totalsales=0;
				var $d= $clcnt--;				
				if($j<3)
				{
				    var c = 'tot'+$i+$d;
				    var $txt = $('#'+c).text();
				    //totsale +="<td id='grptot"+$j+"' align=right>"+$txt+"</td>";
				    if($j==0)
				    {
					var value = removecomma($txt);
					if(!isNaN(value) && value.length != 0) {
					    $t0 += parseFloat(value);					  
					}				    		
				    }
				    else if($j==1)
				    {
					var value = removecomma($txt);
					if(!isNaN(value) && value.length != 0) {
					    $t1 += parseFloat(value);					  
					}				    		
				    }
				    else if($j==2)
				    {
					var value = removecomma($txt);
					if(!isNaN(value) && value.length != 0) {
					    $t2 += parseFloat(value);					  
					}				    		
				    }
				}				
			    }
			    //totsale +="</tr>";			    
			}
			    totsale +="<tr style='color:#990000;font-size:12px;'>";
			    totsale +="<td align=right>Total: </td>";
			    totsale +="<td align=right>"+commafy($t2)+"</td>";
			    totsale +="<td align=right>"+commafy($t1)+"</td>";
			    totsale +="<td align=right>"+commafy($t0)+"</td>";
			    totsale +="</tr>";
			totsale +="</table></p>";
			for($j=0;$j<3;$j++)
			{
			    var sum = 0;			    
			    var c = 'grptot'+$j;
			    var $t = $('[id^="grptot0"]').text();
			    //$('#cc').html($t);
				$('.'+c).each(function() {				    
				    var value = removecomma($(this).text());				
				    if(!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
					//$('#cc').html(value);
				    }				    				    
				});
				var tot = 'tot'+$i+$j;		
				$('#'+tot).html("<b>"+commafy(sum)+"</b>");
			}
			// total sales
			$('#result_div').append(totsale);
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
		//    else
		//    {
		//	$('#cc').html(response.result);
		//    }
		});
	    });
	//}
    });
    $(function() {
     $(".multiselect").multiselect();
});
   jQuery.fn.multiselect = function() {
    $(this).each(function() {
        var checkboxes = $(this).find("input:checkbox");
        checkboxes.each(function() {
            var checkbox = $(this);
            // Highlight pre-selected checkboxes
            if (checkbox.prop("checked"))
                checkbox.parent().addClass("multiselect-on");
 
            // Highlight checkboxes that the user selects
            checkbox.click(function() {
                if (checkbox.prop("checked"))
                    checkbox.parent().addClass("multiselect-on");
                else
                    checkbox.parent().removeClass("multiselect-on");
            });
        });
    });
};
});	
</script>
</head>

<body id="dt_example" style="width: 90%">
<form id="myForm" name="myForm" action="" method="post">
    <p><font size="+1" color="#000066"> <b> INVOICE DETAILS</font> </p><hr color="#000066" height="2px"></br><br>
    <div style='text-align: left'>
	From
	<input type='text' name='invdtfrom' id='invdtfrom' class ='dtclass' value='<?php  echo $start;?>' readonly style='width: 150px;'/>
	To
	<input type='text' name='invdtto' id='invdtto' class ='dtclass' value='<?php  echo $end;?>' readonly style='width: 150px;'/>    
	<select id="buildingmasid" name="buildingmasid" style='width: 130px;'>
	    <option value=0 selected>Building</option>
	    <?php loadBuilding();?>
	</select>	
	<!--<select id="grouptenantmasid" name="grouptenantmasid" style='width: 500px;'>
	    <option value=0 selected>Tenant</option>        
	</select>-->
	</br></br>
	<div class="multiselect" style='text-align: left'>
	    <?php loadinvoicedesc();?>
	</div>
	</br>	</br>	
	<button type="button" id="btnList">List Invoices</button>
	<button type="button" id="btnKiosk">Kiosks and trolley Sales</button>
	<button type="button" id="btnTotSales">Total Sales</button>
	<button type="button" id="btnPrint">Print</button>	
    </div>
   
    </br>	    
    <div style="position: absolute; left: 710px; top: 165px; width: 500px;">
	
    </div>
    </br>
    &nbsp;&nbsp;<font color=red><label id="cc"></label></font>
    </br>   
   
    <div id='result_div'>
    
    </div>
</form>
<br>
</body>
</html>

