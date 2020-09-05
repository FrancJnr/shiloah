<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Manual Invoice</title>
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
        header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
   // include('ip_test.php');
    function loadcompany()
    {
	$companymasid = $_SESSION['mycompanymasid'];
	if($companymasid == '2') // GRANDWAYS VENTURES LTD
	    $sql = "select companymasid,companyname,pin,vatno from mas_company where companymasid = '$companymasid' order by companymasid;";
	else
	    $sql = "select companymasid,companyname,pin,vatno from mas_company where companymasid != '2' order by companymasid;";
	
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['companymasid'].">".$row['companyname']."</option>");		
                }
        }
    }
    function loadbuilding()
    {
		$companymasid = $_SESSION['mycompanymasid'];
		$sql = "select buildingmasid, buildingname from mas_building WHERE companymasid =".$companymasid." order by buildingname asc";
      //  $sql = "select buildingmasid, buildingname from mas_building order by buildingmasid";
        $result = mysql_query($sql);
        if($result != null)
        {
                while($row = mysql_fetch_assoc($result))
                {
                        echo("<option value=".$row['buildingmasid'].">".$row['buildingname']."</option>");		
                }
        }
    }  
    function loadinvoicedesc()
    {
	    $sql = "select * from invoice_desc where active='1'";
	    $result = mysql_query($sql);
	    if($result != null)
	    {
		    while($row = mysql_fetch_assoc($result))
		    {
			    echo("<option value=".$row['invoicedescmasid'].">".$row['invoicedesc']."</option>");		
		    }
	    }
    }   
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$(".sundry").hide();
    invnocheck($("#companymasid").val());
    $("#companymasid").focus();
    $(".datepick").datepicker({
            showOn: "button",
	    buttonImage: "../images/calendar.gif",
	    buttonImageOnly: true,
	    changeMonth: true,
	    changeYear: true,
	    dateFormat:"dd M yy",
	    showButtonPanel: true,
	    onClose: function() {
		var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
		var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
		//$(this).datepicker('setDate', new Date(iYear, iMonth, iDate));		
	    }
    });
    
    $('[id^="buildingmasid"]').live('change', function() {
	$('#premise').text("");
	if($(this).val() >0)
	{
	    var str= $('#buildingmasid option:selected').text();
	    $('#premise').text(str +" - ");
	}
    });
    $('[id^="companymasid"]').live('change', function() {
	$('#toaddress').val("");
	$('#premise').val("");
	$('#buildingmasid').val("");
        invnocheck($(this).val());
	compmasid = $(this).val();
	loadgrouptenant("manual_invoice",compmasid);
    });
    function invnocheck(itemtype){
	var url="load_details.php?action=invno";
	var dataToBeSent = $("form").serialize();	
	$.getJSON(url,dataToBeSent, function(data){
	    $.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {
			$('#invoiceno').val(response.result);
			$('#buildingmasid').empty();
			$('#buildingmasid').append( new Option("Select","",true,false));						
			$.each(data.myResult, function(i,response){
			    var a= response.buildingname;
			    var b = response.buildingmasid
			    $('#buildingmasid').append( new Option(a,b,true,false) );
			});
			$('#buildingmasid').append( new Option("Others","0",true,false));
		    }
		    else
		    {
			$('#cc').html(response.result);
		    }
	    });
	});
    }
    function invnocheck_one(itemtype){
	var url="load_details.php?action=invno";
	var dataToBeSent = $("form").serialize();	
	$.getJSON(url,dataToBeSent, function(data){
	    $.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {
			$('#invoiceno').val(response.result);
		    }
		    else
		    {
			$('#cc').html(response.result);
		    }
	    });
	});
    }    
    $('[id^="btnDraft"]').live('click', function() {
	    if($("#buildingmasid").val() =="")
	    {
		alert("Building Selection is mandatory.");
		$("#buildingmasid").focus();
		return false;
	    }
	    if($("#invoicedescmasid").val() =="")
	    {
		alert("Invoice Description is mandatory.");
		$("#invoicedescmasid").focus();
		return false;
	    }
	    if($('.value').val() =="")
	    {
		alert("Invoice Value is mandatory.");
		$("#value").focus();
		return false;
	    }
	    var r = confirm("can you confirm this?");
	    if(r==true)
	    {
	        var dataToBeSent = $("form").serialize();
	        window.open("view_manual_invoice.php?"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
	        return false;
	    }		  
    });
    $('[id^="btnSave"]').live('click', function() {
	    if($("#grouptenantmasid").val() =="")
	    {
		alert("Please Select Tenant.");
		$("#grouptenantmasid").focus();
		return false;
	    }
		if($("#grouptenantmasid").val() ==0 && $("#pin").val() =="")
	    {
		alert("Please Enter PIN.");
		$("#pin").focus();
		
		return false;
	    }
		if($("#grouptenantmasid").val() ==0 && $("#leasename").val() =="")
	    {
		alert("Please Enter Leasename.");
		$("#leasename").focus();
		
		return false;
	    }
	    if($("#toaddress").val() =="")
	    {
		alert("To address is mandatory.");
		$("#toaddress").focus();
		return false;
	    }
	    if($("#premise").val() =="")
	    {
		alert("Premise details are mandatory.");
		$("#premise").focus();
		return false;
	    }
	    if($("#buildingmasid").val() =="")
	    {
		alert("Building Selection is mandatory.");
		$("#buildingmasid").focus();
		return false;
	    }
	    if($("#invoicedescmasid").val() =="")
	    {
		alert("Invoice Description is mandatory.");
		$("#invoicedescmasid").focus();
		return false;
	    }
	    if($('.value').val() =="")
	    {
		alert("Invoice Value is mandatory.");
		$("#value").focus();
		return false;
	    }
	    var r = confirm("Are you sure. You Want to Save?");
	    if(r==true)
	    {
	        var dataToBeSent = $("form").serialize();
	        window.open("save_manual_invoice.php?"+dataToBeSent, "Print PDF", "width=800,height=800,toolbar:false,");
	        return false;
	    }	    
    });
    var companymasid = $('#companymasid').val();
    loadgrouptenant("manual_invoice",companymasid);
    function loadgrouptenant(itemtype,compmasid)
    {
	var url="../reports-pms/load_report.php?item="+itemtype+"&compmasid="+compmasid;	
	$.getJSON(url,function(data){
	    $.each(data.error, function(i,response){
		if(response.s == "Success")
		{
		    $('#grouptenantmasid').empty();
		    $('#grouptenantmasid').append( new Option("Select","",true,false) );
		    $('#grouptenantmasid').append( new Option("SUNDRY DEBTOR","0",true,false) );
		    $.each(data.myResult, function(i,response){
			    var t = response.tradingname;
			    if(t !="")
				var b = response.tradingname;
			    else
				var b = response.leasename;
			    var r = response.renewalfromid;
			    if(r <=0)
				var a = b+" ("+response.shopcode+")";
			    else
				var a = b+" ("+response.shopcode+" RENEWED)" ;
			    $('#grouptenantmasid').append( new Option(a,response.grouptenantmasid,true,false) );
		    });
		}
		else
		{
		    $('#grouptenantmasid').empty();
		    $('#grouptenantmasid').append( new Option("Select","",true,false) );
		    $('#cc').html(response.s);
		}
	    });		
	});
    }
    $('[id^="grouptenantmasid"]').live('change', function() {	        
	var grouptenantmasid = $(this).val();
	if ((grouptenantmasid == "") || (grouptenantmasid == 0))
	{
	    $('#toaddress').val('');
	    $('#premise').val('');
	    $('#buildingmasid').val('');
		$(".sundry").show();
	}
	else
	{	    
	    if(grouptenantmasid != "0")
	    {
	    $(".sundry").hide();
		var url="load_details.php?action=tenantdetails&grouptenantmasid="+grouptenantmasid;
		//alert(grouptenantmasid);
		var dataToBeSent = $("form").serialize();	
		$.getJSON(url,dataToBeSent, function(data){
		    $.each(data.error, function(i,response){
			    if(response.s == "Success")
			    {
				$('#companymasid').val(response.companymasid);
				invnocheck_one(response.companymasid);
				
				$('#toaddress').val(response.tenantaddress);
				$('#premise').val(response.buildingaddress);
				$('#buildingmasid').val(response.buildingmasid);
			    }
			    else
			    {
				$('#cc').html(response.result);
			    }
		    });
		});
	    }
	    
	}
    });
    $('[class^="invdesc"]').live('change', function() {
	var a = $(this).attr("name");	
	var b = $(this).val();
	calcvat(a,b);	
    });    
    $('[id^="value"]').live('keyup', function() {
	var str = $(this).attr("name");		
	var ret = str.split("_");
	var len = (ret.length);
	var str2="01";
	if (len > 1)
	{	    	    
	    str2 = ret[1];	
	}
	var a = "invoicedescmasid_"+str2;
	var b = $('[name="invoicedescmasid_'+str2+'"]').val();	
	calcvat(a,b);
	$('#totalvalue').val($get_total("value"));
	$('#totalvat').val($get_total("vat"));
	$('#totalamount').val($get_total("total"));
    });
    function calcvat(str,v)
    {	
	var ret = str.split("_");
	var len = (ret.length);
	var str2="01";
	if (len > 1)
	{	    	    
	    str2 = ret[1];	
	}
	var url="load_details.php?action=vat&invoicedescmasid="+v;		
	var dataToBeSent = $("form").serialize();	
	$.getJSON(url,dataToBeSent, function(data){
	    $.each(data.error, function(i,response){
		    if(response.s == "Success")
		    {			
			var vat = response.vat;
			var val1 = $('[name="value_'+str2+'"]').val()
			var val2 = Math.round(val1*vat/100);
			$('[name="vat_'+str2+'"]').val(val2);
			var val3 = parseInt(val1)+parseInt(val2);
			$('[name="total_'+str2+'"]').val(val3);
			
			$('#totalvalue').val($get_total("value"));
			$('#totalvat').val($get_total("vat"));
			$('#totalamount').val($get_total("total"));
		    }
		    else
		    {
			$('#cc').html(response.result);
		    }
	    });
	});	
    }
    
    $('#btnAdd').live("click", function(){
	if($('#invoice_item_table tr').size() <= 9){
	    $get_lastID();
	    $('#invoice_item_table tbody').append($newRow);
	} else {
	    alert("Reached Maximum Rows!");
	};
    });
    $(".del_table_row").live("click", function(){ 
	$(this).closest('tr').remove();
	$('#totalvalue').val($get_total("value"));
	$('#totalvat').val($get_total("vat"));
	$('#totalamount').val($get_total("total"));
	$lastChar = $lastChar-2;	
    });
    $get_lastID = function(){
	 var $id = $('#invoice_item_table tr:last-child td:first-child input').attr("name");
	 var $val = $('#invoice_item_table tr:last-child td:first-child input').attr("value");
	 $val =parseInt($val)+1;
	 $lastChar = parseInt($id.substr($id.length - 2), 10);
	 $lastChar = $lastChar + 1;
	 $newRow = "<tr>\
		     <td><input type='text' name='sno_0"+$lastChar+"' maxlength='255' value='"+$val+"'/></td> \
		     <td><select id='invoicedescmasid_0"+$lastChar+"' name='invoicedescmasid_0"+$lastChar+"' class='invdesc' style='width:250px;'><option value='' selected>----Select Invoice Desc----</option><?php loadinvoicedesc();?></select></td> \
		     <td><input type='text' class='value' id='value' name='value_0"+$lastChar+"' maxlength='255' /></td> \
		     <td><input type='text' class='vat' id='vat' name='vat_0"+$lastChar+"' maxlength='255'readonly /></td> \
		     <td><input type='text' class='total' name='total_0"+$lastChar+"' maxlength='255' readonly/></td> \
		     <td><img src='../images/delete.png' class='del_table_row'></td> \
		 </tr>"
	 return $newRow;
    }
    $('[class^="value"]').live('blur', function() {	
	$('#totalvalue').val($get_total("value"));
	$('#totalvat').val($get_total("vat"));
	$('#totalamount').val($get_total("total"));
    });  
    $get_total = function(a){	
	var total = 0;   
	$('[class^="'+a+'"]').each( function(){
	    total += $(this).val() * 1;				
	});
	return total;
	//return commafy(total);
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
input[type=text]
{
    color:#00c6c6;
    height: 28px;
    width: 120px;
    padding-left: 10px;
    text-decoration: none;    
    background-repeat: repeat-x;
    border-radius: 5px; /*up to date browsers support this, but you can add prefixes if you want*/
    border: 0;
}    
</style>
</head>
<body id="dt_example" style="width: 90%">
<form id="myForm" name="myForm" action="" method="post">
    <br>
    <h1 align='CENTER' style="background-color:#00c6c6;color: #000000">Manual Invoice</h1>
    <br>
	<font color=red><label id="cc"></label></font>	
    <br>   
    <table class='table6'>	
        <tr>
            <td>Company<font color="red">*</font></td>
            <td>
                <select id="companymasid" name="companymasid" style='width: 225px;'>                    
				<?php loadcompany();?>
			    </select>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Invoice No <input type="text" id="invoiceno" name="invoiceno" style="font-size: larger;" readonly/>
            </td>
        </tr>	
        <tr>
		<td>
			From<font color='red'>*</font>
		</td>
		<td>
			<input type='text' name='fromdate' id='fromdate' class="datepick" value='<?php  echo date('01 M Y');?>' readonly/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			To<font color='red'>*</font>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type='text' name='todate' id='todate' class="datepick" value='<?php  echo date('t M Y');?>' readonly/>
		</td>
	</tr>
	<tr>
		<td>Tenant<font color="red">*</font></td>
		<td>
		    <select id="grouptenantmasid" name="grouptenantmasid" style='width:525px;'>
			<option value="" selected>Select</option>			
		    </select>
		</td>
	</tr>
	<tr  class="sundry">
		<td>
			PIN:<font color='red'>*</font>
		</td>
		<td>			
			<input type="text" class='sundry' id="pin" name="pin" maxlength="11"  />
		</td>
	</tr>	
	
	<tr class="sundry">
		<td>
			Lease Name:<font color='red'>*</font>
		</td>
		<td>			
			<textarea class ="sundry" cols=100 rows=2 id="leasename" name="leasename"></textarea>
		</td>
	</tr>	
	<tr>
		<td>
			To:<font color='red'>*</font>
		</td>
		<td>			
			<textarea cols=100 rows=5 id="toaddress" name="toaddress"></textarea>
		</td>
	</tr>
	<tr>
		<td>Building<font color="red">*</font></td>
		<td>
		   
		    <select id="buildingmasid" name="buildingmasid" style='width: 225px;'>
				<option value='' selected='selected'>Select</option>								
			    </select>
		</td>
	</tr>	
	<tr>
		<td>
			Premise:<font color='red'>*</font>
		</td>
		<td>			
			<textarea cols=100 rows=5 id="premise" name="premise"></textarea>
		</td>
	</tr>	
	<tr>
		<td colspan="2">
		    <table id="invoice_item_table" cellspacing="0" cellpadding="0" class='table6'>
		    <thead>
			<tr>
			    <th>S.No</th>
			    <th>Description</th>
			    <th>Amount</th>
			    <th>Vat</th>
			    <th>Total</th>			    
			</tr>
			</thead>
			<tbody>
			    <tr>
				<td><input type="text" name="sno_01" maxlength="255" required value="1"/></td>
				<td>
				    <select class="invdesc" id="invoicedescmasid" name="invoicedescmasid_01" style='width:250px;'>
					<option value="" selected>----Select Invoice Desc----</option>
					<?php loadinvoicedesc();?>
				    </select>
				</td>
				<td><input type="text" class='value' id="value" name="value_01" maxlength="255"  /></td>
				<td><input type="text" class='vat' id="vat" name="vat_01" maxlength="255" readonly /></td>
				<td><input type="text" class='total' name="total_01" maxlength="255" readonly /></td>
				<td>&nbsp;</td>
			    </tr>
			</tbody>
		    </table>
		    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="btnAdd">Add Row</button>		    
		    <table width="96.5%" class='table6'>
			<tr>			    			    
			    <td style='text-align: right'>
				<b>Grand Total:</b>
				&nbsp;
				<input type="text" id="totalvalue" name="totalvalue" readonly />
				&nbsp;
				<input type="text" id="totalvat" name="totalvat" readonly />
				&nbsp;
				<input type="text" id="totalamount" name="totalamount" readonly/>
			    </td>
			</tr>
		    </table>
		</td>
	</tr>	
	<tr>
		<td>
			Remarks:
		</td>
		<td>
			<textarea cols=100 rows=5 id="remarks" name="remarks"></textarea>
		</td>
	</tr>	
         <tr>		
		<td colspan="2" style='text-align: right'>
		    <!--<button type="button" id="btnDraft">Draft Invoice</button>-->
                        &nbsp;&nbsp;&nbsp;
		    <button type="button" id="btnSave">Save Invoice</button>
		</td>
	</tr>        
    </table>	  
    <p id='heading' align='center'></p>
    <div id='display'>
    
    </div>
        <span class='span_cont'>Available Manual Invoices:</span><div style='height:8px'></div>
<?php
$directory="../../pms_docs/man_invoices/";
$sortOrder="newestFirst"; 
$results = array(); 
$handler = opendir($directory);
   
if (glob($directory . "*.*") != false)
{
    
while ($file = readdir($handler)) {  
       if ($file != '.' && $file != '..' && $file != "robots.txt" && $file != ".htaccess"){ 
           $currentModified = filemtime($directory."/".$file);	   
	   $file_type[] = strtolower(substr($directory."/".$file, strrpos($directory."/".$file, '.')+1));
	   $file_size[] = filesize($directory."/".$file);
           $file_names[] = $file; 
           $file_dates[] = $currentModified; 
       }    
   } 
       closedir($handler); 

   //Sort the date array by preferred order

   if ($sortOrder == "newestFirst"){ 
       arsort($file_dates);
   }else{ 
       asort($file_dates); 
   } 
    $w=1;
   //Match file_names array to file_dates array 
   $file_names_Array = array_keys($file_dates);     
   foreach ($file_names_Array as $idx => $name) $name=$file_names[$name]; 
   $file_dates = array_merge($file_dates); 
   $i = 0;  $date1=0;
   $date = date('d-m-Y', $file_dates[0]);   
   echo "<span class='span_cont'><u>".$date."</u></span><div style='height:8px'></div>";
   echo "<table class='table6'>";
   echo "<tr>
		<th>S.No</th>
		<th>File</th>
		<th>Type</th>
		<th>Size</th>
		<th>Remove</th>
	</tr>";
   //Loop through dates array and then echo the list 
   foreach ($file_dates as $file_dates){       
       $date = date('d-m-Y', $file_dates);       
       $j = $file_names_Array[$i];       
       $file = $file_names[$j];
       $type = $file_type[$j];
       $size = $file_size[$j];
       if($type=="btc")
	$type = "Dbf File";
       else if($type=="txt")
	   $type = "Text File";
        else if($type=="zip")
	  $type = "Zip File";
       
       $i++;
       if($date < $date1)
       {
		echo "</table><div style='height:8px'></div>";
		echo "<span class='span_cont'><u>".$date."</u></span><div style='height:8px'></div>";
		echo "<table class='table6'>";
		echo "<tr>
			<th>S.No</th>
			<th >File</th>
			<th>Type</th>
			<th>Size</th>
			<th>Remove</th>
			</tr>";
		$w=1;
       }
       $link = $directory.$file;
       echo "<tr>";
       echo "<td>$w</td>";
       echo "<td><a href='$link' target='_blank'>$file</a></td>";
       echo "<td>$type</td>";
       echo "<td>".formatSizeUnits($size)."</td>";       
       echo "<td align='center'><a href=\"deletefile.php?file=$link\"><img src='../images/delete.png'></a></td>";
       echo "</tr>";
       //echo  "File name: $file - Date Added:  $date'. $i <br/>";
       $w++;
       $date1 = $date;
   }
}else
{
	echo "No Files in the directory.";
}
function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}

?>
</div> <!--File Details-->
    </form>
    &nbsp;&nbsp;
    <br>
</form>
</body>
</html>

