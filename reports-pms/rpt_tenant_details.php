<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Tenant Details Report</title>
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
?>
<style>
    @import "../css/print.css";   
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
     var options = {
            width: 1200,
            height: 300,
            controls: "bold italic underline strikethrough subscript superscript | font size " +
                    "style | color highlight removeformat | bullets numbering | outdent " +
                    "indent | alignleft center alignright justify | undo redo | " +
                    "rule link unlink | cut copy paste pastetext "
        };      
	$("#grouptenantmasid").autocomplete({
            //source:"load_report.php?item=loadtenantdetails&buildingmasid="+$('#buildingmasid').val(),
            //minLength:1,
            //select: function(event, ui) {				
            //        $("#hid_itemid").val(ui.item.id);							
            //}
	    source: function(request, response) {
		$.ajax({
		    url: "../reports-pms/load_report.php",
		    dataType: "json",
		    data: {
			term: request.term,
			item: 'loadtenantdetails', 
			buildingmasid: $("#buildingmasid").val(),
			tenantstate: $("#tenantstate").val(),
			searchtype: $("#searchtype").val()
		    },
		    success: function(data) {
			response(data);
		    }
		});
	    },
	    minLength:1,
	    select: function(event, ui) {				
                $("#hid_itemid").val(ui.item.id);
	    }
        });        	
	//$('[id^="btnPrint"]').live('click', function() {
	//	$('.printable').print();
	//});
	//$("#btnExport").click(function(e) {
	//	window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#buildingDiv').html()));
	//	e.preventDefault();
	//});
	$('[id^="btnView"]').live('click', function() {				
	    disp_details();
	});
	function disp_details()
	{
            $('#tenantstatus').html('');
	    $('#shopoccupied').html('');
	    $('#divContent').empty();		
            var str= $('#grouptenantmasid').val();
            if(str=="")
	    $('#grouptenant').empty();
	    $('.content').each(function() {	
		var k = $(this).text("");
	    });
            var temp = new Array();
            temp = str.split("-"); //split -
            temp = temp[1].split(")"); //split ')'
            temp[0]; // building shortname from lease name and tenant code
            var hidval = $("#hid_itemid").val();
	    //original data
            var url="load_report.php?item=tenantdetails&itemval='"+hidval+"'&buildingshortname="+temp[0];		
            $.getJSON(url,function(data){
                    $.each(data.error, function(i,response){
			    ////$('#cc').html(response.msg);
                            $.each(data.myResult, function(i,response){
                                    //$('#grouptenant').html(response.leasename+" <strong>("+response.shopcode+","+response.size+","+response.tenantcode+")<input type='hidden' name='tenantmasid"+response.tenantmasid+"' value='"+response.tenantmasid+"'>");
				    $('#tenancyrefcode').html(response.tenancyrefcode);
				    $('#pin').html(response.pin);
				    $('#leasename').html(response.leasename);
				    $('#tenanttype').html(response.tenanttype);
				    $('#tradingname').html(response.tradingname);
				    var a = "Name:"+response.cpname+"</br>Pin: "+response.cpnid+"</br>Mobile: "+response.cpmobile+"</br>Landline:"+response.cplandline+"</br>Email: "+response.cpemailid;
				    $('#address').html(a);
				    $('#buildingname').html(response.buildingname);
				    $('#shopcode').html(response.shopcode);
				    $('#size').html(response.size);
				    $('#leaseterm').html(response.leaseterm);
				    $('#rentcycle').html(response.rentcycle);
				    $('#nob').html(response.nob);
				    
				    var active = response.active;
				    if(active ==1)
					active = "<font color='green'>Active";				    
				    else				    
					active = "<font color='red'>Not Active";
					
				    $('#tenantstatus').html(active);
				    
				    var renewal = response.renewalfromid;
				   if(renewal >0)
					renewal = "<font color='green'>Renewed";
				    else
					renewal = "-";
				     $('#renewal').html(renewal);	
					
				    var shopoccupied = response.shopoccupied;
				    
				    if(shopoccupied ==1)
					shopoccupied= "<font color='green'>Occupied";
				    else
					shopoccupied= "<font color='red'>Waiting List";
					
				    $('#shopoccupied').html(shopoccupied);
				    
				    var waitinglist = response.waitinglist;
				    if(waitinglist >0)
					waitinglist= "<font color='red'>Yes";
				    else
					waitinglist="<font color='green'>No";
				    $('#waitinglist').html(waitinglist);
				    $('#docdt').html(response.docdt);
				    $('#expdt').html(response.expdt);				    
				    $('#includedby').html("<font color='red'> "+response.includedby+"</font>");
				    $('#includedon').html("<font color='red'> "+response.includedon+"</font>");
				    var leasestate = response.leasestate;
				    if(leasestate >0){
					offerletterstate = "<font color='green'>Yes"
					leasestate= "<font color='green'>Active";
				    }
				    else{
					if(response.offerlettermasid ==null)
					{
						offerletterstate = "<font color='red'>No";
						leasestate="<font color='red'>Not Active";	
					}
					else
					{
						leasestate="<font color='red'>Expired";	
					}					
				    }
				    $('#offerletterstate').html(offerletterstate);
				    $('#leasestate').html(leasestate);
				     
				     
				     
				     var add = response.address1+" <br>"+ response.address2;
				     var tenantcontactno = response.address1+" <br>"+ response.address2+"</br>Phone: "+response.telephone1+" / "+response.telephone2+"</br>P.O.Box No: "+response.poboxno+"-"+response.pincode+"</br>"+response.city;
				     $('#companyaddress').html(tenantcontactno);
				     $('#otherdet').html(
							"tmasid :"+ response.tenantmasid +" &nbsp;&nbsp;,&nbsp;&nbsp;" +
							"offletmasid :"+ response.offerlettermasid +" &nbsp;&nbsp;,&nbsp;&nbsp;" +
							"gpmasid :"+ response.grouptenantmasid +" &nbsp;&nbsp;,&nbsp;&nbsp;" 
							);
				     
                            });
                    });		
            });
	    
	//offer letter details
	var url="load_report.php?item=tenantdetails_rent&itemval='"+hidval+"'&buildingshortname="+temp[0];		
        $.getJSON(url,function(data){
		$.each(data.error, function(i,response){
			if(response.s == "Success")			
			{
				$('#offerletter').html(response.msg);
			}
		});
	});
	var url="load_report.php?item=rec_tenantdetails_rent&itemval='"+hidval+"'&buildingshortname="+temp[0];		
        $.getJSON(url,function(data){
		$.each(data.error, function(i,response){
			if(response.s == "Success")			
			{
				$('#recofferletter').html(response.msg);
			}
		});
	});
	//invoice details
	var url="load_report.php?item=tenantdetails_invoice&itemval='"+hidval+"'&buildingshortname="+temp[0];		
        $.getJSON(url,function(data){
		$.each(data.error, function(i,response){
			if(response.s == "Success")			
			{
				$('#invoice').html(response.msg);
			}
		});
	});
	//rectified details
	$('#rectificationstate').html('');
            var url="load_report.php?item=rec_tenantdetails&itemval='"+hidval+"'&buildingshortname="+temp[0];		
            $.getJSON(url,function(data){
                    $.each(data.error, function(i,response){
                            $.each(data.myResult, function(i,response){                                    
				    $('#recleasename').html(response.leasename);
				    $('#rectradingname').html(response.tradingname);
				    var a = "Name:"+response.cpname+"</br>Pin: "+response.cpnid+"</br>Mobile: "+response.cpmobile+"</br>Landline:"+response.cplandline+"</br>Email: "+response.cpemailid;
				    $('#recaddress').html(a);				    
				    $('#recshopcode').html(response.shopcode);
				    $('#recsize').html(response.size);
				    $('#recleaseterm').html(response.leaseterm);
				    $('#recrentcycle').html(response.rentcycle);
				    $('#recnob').html(response.nob);
				    
				 var active = response.active;
				    if(active ==1)
					active = "<font color='green'>Active";				    
				    else				    
					active = "<font color='red'>Not Active";
					
				    $('#tenantstatus').html(active);
				    
				    var renewal = response.renewalfromid;
				   if(renewal >0)
					renewal = "<font color='green'>Renewed";
				    else
					renewal = "<font color='red'>-";
				     $('#renewal').html(renewal);	
					
				    var shopoccupied = response.shopoccupied;
				    
				    if(shopoccupied ==1)
					shopoccupied= "<font color='green'>Occupied";
				    else
					shopoccupied= "<font color='red'>Waiting List";
					
				    $('#shopoccupied').html(shopoccupied);
				    
				    var rectified = "<font color='red'>Rectified on: "+ response.createddatetime+" by: "+response.createdby;
				    $('#rectificationstate').html(rectified);
                            });
                    });		
            });
            return;
	}
});
</script>
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="#" method="post">
    <p><font size="+1" color="#000066"> <b> TENANT SEARCH REPORT *</b> </font> </p><hr color="#000066" height="2px"></br>
    Building:
    <select id="buildingmasid" name="buildingmasid" style='width: 130px;'>
	<option value=0 selected>Building</option>
		<?php loadBuilding();?>
    </select>
    Tenant State:
    <select id="tenantstate" name="tenantstate" style='width: 130px;'>
	<option value=0 selected>Active</option>
	<option value=1>Partial</option>		
	<option value=2>Expired</option>
	<option value=3>All</option>
    </select>	   
     Search By:
    <select id="searchtype" name="searchtype" style='width: 130px;'>
	<option value=0 selected>Tenancy code</option>
	<option value=1>Tenant name</option>
    </select>    
    <input type="text" id="grouptenantmasid" name="grouptenantmasid" style="width:500px;"/>
    <input type="hidden" id="hid_itemid" name="hid_itemid" value="0"/>
    <button type="button" id="btnView">View</button>
    <!--<button type="button" id="btnPrint">Print</button>
    <button type="button" id="btnExport">Export</button>-->
    </br></br>
    <font color=red><label id="cc"></label></font>
    <p class='printable'>
    <table class='table6' width='97%'>	
        <tr>
            <td colspan='2'>
		<table>
			<tr>				
				<th>Tenant Status</th>								
				<th>Shop Status</th>
				<th>Renewal Status</th>				
				<th>Lease Period</th>
				<th>DOC</th>
				<th>Exp Dt</th>
				<th>Included By</th>
				<th>Included On</th>
				
			</tr>
			<tr>				
				<td id='tenantstatus'></td>								
				<td id='shopoccupied'></td>
				<td id='renewal'></td>					
				<td id='leasestate'></td>
				<td id='docdt'></td>
				<td id='expdt'></td>
				<td id='includedby'></td>
				<td id='includedon'></td>
			</tr>
		</table>
	    </td>
        </tr>	
        <tr>
            <td colspan='2'>
                <table width='100%'>
		   <tr>
			<th width='20%'>Title</th><th width='40%'>Original Data</th><th width='40%'>Rectified</th>
		   </tr>
		    <tr style>
                        <td>Tenancy Code:</td>
                        <td class='content' id='tenancyrefcode'></td>
			<td class='content' id=''></td>
                    </tr>
		    <tr style>
                        <td>Pin No:</td>
                        <td class='content' id='pin'></td>
			<td class='content' id=''></td>
                    </tr>
		    <tr style>
                        <td>Lease Name:</td>
                        <td class='content' id='leasename'></td>
			<td class='content' id='recleasename'></td>
                    </tr>		    
		    <tr style>
                        <td>Tenant Type:</td>
                        <td class='content' id='tenanttype'></td>
			<td class='content'></td>
                    </tr>
                    <tr>
                        <td>Trading Name:</td>
                        <td class='content' id='tradingname'></td>
			<td class='content' id='rectradingname'></td>
                    </tr>
		    <tr>
                        <td>Address:</td>
                        <td class='content' id='companyaddress'></td>
			<td class='content' id='reccompanyaddress'></td>
                    </tr>
                    <tr>
                        <td>Contact Person:</td>
                        <td class='content' id='address'></td>
			<td class='content' id='recaddress'></td>
                    </tr>
		    <tr>
                        <td>Building:</td>
                        <td class='content' id='buildingname'></td>
			<td class='content' id='recbuildingname'>-</td>
                    </tr>
		    <tr>
                        <td>Shop:</td>
                        <td class='content' id='shopcode'></td>
			<td class='content' id='recshopcode'>-</td>
                    </tr>
		    <tr>
                        <td>Sqrft:</td>
                        <td class='content' id='size'></td>
			<td class='content' id='recsize'>-</td>
                    </tr>
		    <tr>
                        <td>Lease Term:</td>
                        <td class='content' id='leaseterm'></td>
			<td class='content' id='recleaseterm'></td>
                    </tr>
		    <tr>
                        <td>Rent Cycle:</td>
                        <td class='content' id='rentcycle'></td>
			<td class='content' id='recrentcycle'></td>
                    </tr>
		    <tr>
                        <td>Nature Of Business:</td>
                        <td class='content' id='nob'></td>
			<td class='content' id='recnob'></td>
                    </tr>
		     <tr>
                        <td>Doc:</td>
                        <td class='content' id='docdt'></td>
			<td class='content' id='recdocdt'>-</td>
                    </tr>
		     <tr>
                        <td>Expiry:</td>
                        <td class='content' id='expdt'></td>
			<td class='content' id='recexpdt'>-</td>
                    </tr>		    
		</table>		
            </td>	    
        </tr>
	<tr>
		<th width='50%'>Offerletter</th><th width='50%'>Rectification</th>
	</tr>
	<tr>
		<td class='content' id='offerletter'>oooooo</td>
		<td class='content' id='recofferletter'>mmmmmmmm</td>
	</tr>	
	<tr>
		<td class='content' id='invoice'></td>			
	</tr>
	<tr>
            <td colspan='2'><b>Other Details:</b></td>
        </tr>
	<tr>
            <td colspan='2' class='content' id='otherdet'></td>
	</tr>
    </table>
    </p>
    <div id="divContent">
    </div>
</form>
</body>
</html>
