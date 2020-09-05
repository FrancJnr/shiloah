<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Generator Cost Apportionment</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
    function loadBuilding()
    {
        $sql = "select buildingmasid, buildingname from mas_building where buildingmasid !='6'";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['buildingmasid'].">".$row['buildingname']."</option>");		
                }
        }
    }
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    $('#buildingmasid1')[0].focus()
    $('#buildinglistTbl').hide();    
    $('[id="buildingmasid1"]').live('change', function() {
	$('#transexpmasid1').empty();
	$('#transexpmasid1').append( new Option("-----Select Date Range-----","",true,false) );
	$('#report').html("");
	$('#cc').html("");
        loaddaterange($(this).val(),'1');
    });  
    $('[id="buildingmasid3"]').live('change', function() {
	$('#transexpmasid3').empty();
	$('#transexpmasid3').append( new Option("-----Select Date Range-----","",true,false) );
	$('#report').html("");
	$('#cc').html("");
        loaddaterange($(this).val(),'3');
    });
    function loaddaterange($x,$y)
    {        	
	var url="load_exp.php?item=loaddaterange&itemval="+$x;					
        $.getJSON(url,function(data){
            $.each(data.error, function(i,response){
                if(response.s == "Success")
                {
                    $('#transexpmasid'+$y).empty();
                    $('#transexpmasid'+$y).append( new Option("-----Select Date Range-----","",true,false) );		
                    $.each(data.myResult, function(i,response){
                            var a = response.fromdate +" to "+ response.todate;
                            $('#transexpmasid'+$y).append( new Option(a,response.transexpmasid,true,false) );
                    });
                }
                else
                {
                    alert(response.s);
                }
            });		
        });
        $("#transexpmasid"+$y).focus();
    }
    $('[id="btnView"]').live('click', function() {
	$('#result').html("");
	if($("#buildingmasid1 option:selected").val()== "")
	{
		alert("Please select building");
		$('#buildingmasid1')[0].focus()
		return false;
	}
	if($("#transexpmasid1 option:selected").val()== "")
	{
		alert("Please select period");
		$('#transexpmasid1')[0].focus()
		return false;
	}
	scview();
    });
    function scview()
     {        
	var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
        if($('#buildingmasid').val()!="")
        {            
            $('#cc').html("");
	    var url="load_gen_det.php";
            var dataToBeSent =  $("#form").serialize();	     
            $.getJSON(url,dataToBeSent, function(data){				
                $.each(data.error, function(i,response){
                    if(response.s == "Success")
                    {
			$('#result').html(response.msg);
			total1('totalsqrft');total1('totalsqrftmc');// send class name			
		    }
		    else
		    {
			$('#result').html(response.msg);
		    }
                });
            });
        }      
    }
    $('[id="btnReport"]').live('click', function() {
	$('#result').html("");
	if($("#buildingmasid1 option:selected").val()== "")
	{
		alert("Please select building");
		$('#buildingmasid1')[0].focus()
		return false;
	}
	if($("#transexpmasid1 option:selected").val()== "")
	{
		alert("Please select period");
		$('#transexpmasid1')[0].focus()
		return false;
	}
	screport();
    });    
    function screport()
     {        
	var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
        if($('#buildingmasid').val()!="")
        {            
            $('#cc').html("");
	    var url="view_gen_det.php";
            var dataToBeSent =  $("#form").serialize();	     
            $.getJSON(url,dataToBeSent, function(data){				
                $.each(data.error, function(i,response){
                    if(response.s == "Success")
                    {
			$('#result').html(response.msg);
			total1('totalsqrft');total1('totalsqrftmc');// send class name			
		    }
		    else
		    {
			$('#result').html(response.msg);
		    }
                });
            });
        }      
    }
    $('[class="txtGen"]').live('change', function() {	
	var n = $(this).attr("idd");	
	var connkw = 'connkw'+n;
	var addikw = 'addikw'+n;
	var totalkw = 'totalkw'+n;
	var totalkva = 'totalkva'+n;
	var totkw = parseFloat($('#'+connkw).val())+parseFloat($('#'+addikw).val());	
	var totkva = parseFloat(totkw/parseFloat($('#kwvalue').val()));	
	var tstkva = totkw+"/"+$('#kwvalue').val();	
	
	$('#'+totalkw).html(parseFloat(totkw));
	$('#'+totalkva).html(parseFloat(totkva));
	
	///hidden value
	var hidtotalkw = 'hid_totalkw'+n;
	var hidtotalkva = 'hid_totalkva'+n;
	$('#'+hidtotalkw).val(parseFloat(totkw));
	$('#'+hidtotalkva).val(parseFloat(totkva));
	
	//grand total
	total2('totalkw');total2('totalkva');total2('totalkvapc');// send class name
	total3('txtGenCommon');	
	totalKVA();
    });
    //common area kw
    $('[class="txtGenCommon"]').live('change', function() {	
	var n = $(this).attr("idd");
	var commonkw = 'commonkw'+n;
	var totalkw = 'totalkw'+n;
	var totalkva = 'totalkva'+n;
	$('#'+totalkw).html($('#'+commonkw).val());
	var tkva = parseFloat($('#'+commonkw).val()/parseFloat($('#kwvalue').val()));
	$('#'+totalkva).html(tkva);
	
	///hidden value
	var hidtotalkw = 'hid_totalkw'+n;
	var hidtotalkva = 'hid_totalkva'+n;
	$('#'+hidtotalkw).val(parseFloat(tkva));
	$('#'+hidtotalkva).val(parseFloat(tkva));
	
	var v =  parseFloat($('#'+commonkw).val());
	total3('txtGenCommon');	
    });
    $('[id="btnExport"]').live('click', function() {
	if($("#buildingmasid1 option:selected").val()== "")
	{
		alert("Please select building");
		$('#buildingmasid1')[0].focus()
		return false;
	}
	if($("#transexpmasid1 option:selected").val()== "")
	{
		alert("Please select period");
		$('#transexpmasid1')[0].focus()
		return false;
	}
	var r = confirm("Are you sure, You want to Export?");
	if(r==true)
	{
	    scexport();
	}
    });    
    $("input:file").change(function (){
	var fileid = $(this).attr('id');
	var byteSize = this.files[0].size;
	var suffix = 'KB';
	if (byteSize > 1000) {
	    byteSize = (byteSize / (1024*1024)).toFixed(2);
	    suffix = 'MB';
	    if(parseInt(byteSize) > 20)
	    {
		alert("File size shuolbe below 17 MB, Uploaded file size:"+byteSize + ' ' + suffix);
		$(this).val('');
		return false;
	    }
	}
	if(fileid =="excelfile")
	{		
		var AllowedExtension = ['xlsx', 'xls'];
		var msg = "Only \'xlsx\', \'xls\' extesions are allowed."; 
	}		
	if ($.inArray($(this).val().split('.').pop().toLowerCase(), AllowedExtension) == -1)
	{
		alert(msg);
		$(this).val('');
	} 				       
    });
    $('[id="btnImport"]').live('click', function() {
	if($("#buildingmasid2 option:selected").val()== "")
	{
		alert("Please select building");
		$('#buildingmasid2')[0].focus()
		return false;
	}
	var r = confirm("Are you sure, You want to Import?");
	if(r==true)
	{
	    scimport();
	}	
    });
    function scimport()
    {
	
	var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
        if($('#buildingmasid').val()!="")
        {
	    $('#cc').html("");
	    var b= $('#buildingmasid').val();
	    var url="import_gen_cost.php?buildingmasid="+b;
	    $("#form2").ajaxSubmit({
		url:url,		
		data:$(this).serialize(),
		datatype:"json",
		beforeSubmit: function() {
		    $('#cc').html('Submitting...');
		},
		success: function(msg) {
		    $('#cc').html(msg);
		}
	});
                
        }
    }
    function scexport()
     {        
	var a = "<strong>"+$('#buildingmasid option:selected').text()+"</strong>";
        if($('#buildingmasid').val()!="")
        {            
            $('#cc').html("");
	    var url="export_gen_cost.php";
            var dataToBeSent =  $("#form1").serialize();	     
            $.getJSON(url,dataToBeSent, function(data){				
                $.each(data.error, function(i,response){
                    if(response.s == "Success")
                    {
                       
			$('#result').html(response.divContent);			
                    }
		    else
		    {
			$('#cc').html(response.result);
		    }                 
                });
            });
                
        }      
     }
     
    $('[id^="btnPrint"]').live('click', function() {
        $('.printable').print();
    });
    $('[id^="btnSave"]').live('click', function() {
	var r = confirm("Are you sure, Can you confirm this action?");
	if(r==true)
	{
	    //("#form0").submit();
	    $('#cc').html("");
	    var url="save_gen_det.php";
	    var dataToBeSent =  $("#form0").serialize();
	    $.getJSON(url,dataToBeSent, function(data){				
		$.each(data.error, function(i,response){		  
		    if(response.s == "Success")
		    {
			$('#result').html(response.msg);
		    }
		    else
		    {
			$('#result').html(response.msg);
		    }
		});
	    });
	}
	
    });
    
});
function total1(totalname)
{
    var sum = 0;
    $('.'+totalname).each(function() {				
	var value = removecomma($(this).text());				
	if(!isNaN(value) && value.length != 0) {
	    sum += parseFloat(value);				
	}				    				    
    });
    $('#'+totalname).html("<font size='2'  color='#990000'>"+commafy(sum)+"</font>");
    //total sqrft in hidden for db
    if(totalname=='totalsqrft')
    {
	$('#hid_totalsqrft').val(sum);
    }
}
function total2(totalname)
{
    var sum = 0;
    $('.'+totalname).each(function() {				
	var value = removecomma($(this).text());				
	if(!isNaN(value) && value.length != 0) {
	    sum += parseFloat(value);	    
	}				    				    
    });
    $('#'+totalname).html("<font size='2'  color='#990000'>"+commafy(Math.round(sum))+"</font>");    
}
// common area
function total3(totalname)
{            
    var commkw = 0;
    $('.'+totalname).each(function() {				
	var value = removecomma($(this).val());				
	if(!isNaN(value) && value.length != 0) {
	    commkw += parseFloat(value);	    
	}				    				    
    });
    commkw = commkw;
    commkw = commkw*parseFloat($('#kwvalue').val());
    //commkw = commkw/parseFloat($('#kwvalue').val());
    var n = $('#lastrow').val();   
    
    var totalkvacom ='totalkvacom'+n;    
    $('#'+totalkvacom).html("<font size='2'  color='#990000'>"+commafy(commkw)+"</font>");
    
    var hidtotalkvacom = 'hid_totalkvacom'+n;
    $('#'+hidtotalkvacom).val(parseFloat(commkw));
       
    
    var sum = 0;
    $('.totalkva').each(function() {				
	var value = removecomma($(this).text());				
	if(!isNaN(value) && value.length != 0) {
	    sum += parseFloat(value);	    
	}				    				    
    });    
    sum = parseFloat(sum);    
    var kvapcval = parseFloat(commkw/sum);
    
    if (isNaN(kvapcval))
    {
	kvapcval=0; 
    }
    else
    {	
	kvapcval = kvapcval*100;
	kvapcval = kvapcval;
    }    
    var netkvacom ='netkvacom'+n;    
    $('#'+netkvacom).html("<font size='2'  color='#990000'>"+kvapcval+"</font>");
    var hidnetkvacom = 'hid_netkvacom'+n;
    $('#'+hidnetkvacom).val(kvapcval);
    
    var chrgddircostcom ='chrgddircostcom'+n;
    var kwvalue = parseFloat($('#appgencost').val())
    var a = parseFloat(kvapcval/100*kwvalue).toFixed(0);
    $('#'+chrgddircostcom).html("<font size='2'  color='#990000'>"+commafy(a)+"</font>");
    
    
    totalKVA();
    $('.chrgdcomcost').each(function() {
	var n = $(this).attr("idd");		
	/// chrged common cost
	var sqrft =  'sqrft'+n;
	var s1 = parseFloat($('#'+sqrft).val());		
	var s2 = parseFloat($('#hid_totalsqrft').val());	
	var b = s1*a/s2;
	if (isNaN(a))
	{
	   b=0; 
	}
	b =b.toFixed(2);
	var chrgdcomcost = 'chrgdcomcost'+n;
	var chrgdcomcost = parseFloat($('#'+chrgdcomcost).html(commafy(b)));
	
	var hidchrgdcomcost = 'hid_chrgdcomcost'+n;
	$('#'+hidchrgdcomcost).val(parseFloat(b));
	
    });   
    $('.gencost').each(function() {
	var n = $(this).attr("idd");
	
	var chrgddircost = 'chrgddircost'+n;
	var chrgddircost = parseFloat(removecomma($('#'+chrgddircost).html()));
	if (isNaN(chrgddircost))
	{
	   chrgddircost=0; 
	}
	var chrgdcomcost = 'chrgdcomcost'+n;
	var chrgdcomcost = parseFloat(removecomma($('#'+chrgdcomcost).html()));
	if (isNaN(chrgdcomcost))
	{
	   chrgdcomcost=0; 
	}
	var gencost = 'gencost'+n;
	var c = parseFloat(chrgddircost+chrgdcomcost).toFixed(2);
	$('#'+gencost).html(commafy(c));
	
	var hidgencost = 'hid_gencost'+n;
	$('#'+hidgencost).val(parseFloat(c));
	
    });
    totalKVA();
    total2('chrgddircost');total2('chrgdcomcost');total2('gencost');
}
function totalKVA()
{

    var sum = 0;
    $('.totalkva').each(function() {				
	var value = removecomma($(this).text());				
	if(!isNaN(value) && value.length != 0) {
	    sum += parseFloat(value);	    
	}				    				    
    });
    $('.totalkvacom').each(function() {				
	var value = removecomma($(this).text());				
	if(!isNaN(value) && value.length != 0) {
	    sum += parseFloat(value);	    
	}				    				    
    });    
    sum = parseFloat(sum);
    
    $('.totalkvapc').each(function() {		
	var n = $(this).attr("idd");	
	var totalkva = 'totalkva'+n;	
	var kva = parseFloat($('#'+totalkva).html());	
	var net = parseFloat(kva/sum*100);	
	if (isNaN(net))
	{
	   net=0; 
	}
	var netkva = 'netkva'+n;
	$('#'+netkva).html(net);	
	
	var hidnetkva = 'hid_netkva'+n;
	$('#'+hidnetkva).val(net)
	
	
	var agc = $('#appgencost').val();	
	var chrgddircost = 'chrgddircost'+n;
	var c = parseFloat(net/100*agc).toFixed(2);
	$('#'+chrgddircost).html(c);
	var chrgddircost = parseFloat($('#'+chrgddircost).html());	
	total2('totalkvapc');// send class name
	
	var hidchrgddircost = 'hid_chrgddircost'+n;
	$('#'+hidchrgddircost).val(parseFloat(c));
    });    
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
</script>
</head>
<body id="dt_example" style="width: 90%">        
    <h1 align='CENTER'>Generator Cost Apportionment</h1>
    <form id="form" name="form" action="" method="post">
	<table class="table6">
	    <tr>
		<th colspan='2'>View Gen Deatails</th>
	    </tr>
	    <tr>
		<td>Building <font color="red">*</font></td>
		<td>
		    <select id="buildingmasid1" name="buildingmasid" style='width: 225px;'>
			<option value="" selected>----Select Building----</option>
			<?php loadBuilding();?>
		    </select>    
		</td>
	    </tr>
	    <tr>
		<td></td>
		<td>
		    <select id="y1" name="y1" style='width: 70px;'>
		    <?php
			foreach(range((int)date("Y"),1978) as $year) {
			    echo "\t<option value='".$year."'>".$year."</option>\n\r";
			}
		    ?>
		    </select> &nbsp;
		    <select id="m1" name="m1" style='width: 70px;'>
			<?php
			    $monthNames = Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
			    foreach($monthNames as $month1) {
				echo "\t<option value='".$month1."'>".$month1."</option>\n\r";
			    }
			?>         
		    </select>
		    &nbsp; TO &nbsp;
		    <select id="m2" name="m2" style='width: 70px;'>
			<?php
			$monthNames = Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
			foreach($monthNames as $month2) {
			    echo "\t<option value='".$month2."'>".$month2."</option>\n\r";
			}
			?>         
		    </select>
		</td>
	    </tr>                 
	    <tr>
		<td></td>
		<td>
		    <button type="button" id="btnView">Enter or Update Data</button>
		    <button type="button" id="btnReport">Report</button>		    
		</td>
	    </tr>                    
	</table>            
    </form>		
    <form id='form0' name='form0' action='save_gen_det.php' method="post">
	<font color=red><label id="cc"></label></font>    
	<div id='result'>
	    
	</div>
    </form> <!--  end of form0-->    
</body>
</html>

