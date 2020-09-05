<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
            header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
   $companymasid = $_SESSION['mycompanymasid'];   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Document Status</title>
<style type="text/css">
#table-3 {
	border: 1px solid #DFDFDF;
	background-color: #F9F9F9;
	width: 100%;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	font-family: Georgia,"Times New Roman","Bitstream Charter",Times,serif;
	font-weight: normal;
	padding: 7px 7px 8px;
	text-align: left;
	line-height: 1.3em;
	font-size: 14px;	
}
#table-3 td, #table-3 th {
	border-top-color: white;
	border-bottom: 1px solid #DFDFDF;
	color: #008000;
}
#table-3 th {
	text-shadow: rgba(255, 255, 255, 0.796875) 0px 1px 0px;
	font-family: Georgia,"Times New Roman","Bitstream Charter",Times,serif;
	font-weight: normal;
	padding: 7px 7px 8px;
	text-align: left;
	line-height: 1.3em;
	font-size: 14px;
}
#table-3 td {
	font-size: 12px;
	padding: 4px 7px 2px;
	vertical-align: top;
}
</style>
<script type="text/javascript" language="javascript">
$(document).focus();
$(document).keydown(function(e){
    if(e.ctrlKey || e.metaKey)
    {
        if (String.fromCharCode(e.charCode||e.keyCode)=="1")	
        {
	    $("#btnNew").click();
            return false;
        }
	if (String.fromCharCode(e.charCode||e.keyCode)=="2")	
        {
	    $("#btnEdit").click();
            return false;
        }
	if (String.fromCharCode(e.charCode||e.keyCode)=="3")	
        {
	    $("#btnView").click();
            return false;
        }
    }
});
$(document).ready(function() {    
    $("#dataManipDiv").hide();
    oTable = $('#example').dataTable({
		"bJQueryUI": true,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
		//"sPaginationType": "full_numbers"			
	});
	$('.datepick').each(function(){
		$(this).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"dd-mm-yy"
		});
	});       
    function clearForm()
	{
		$('#tenantDetails').html('');
                $('#rentbody').html('');
		$('#servicechrgbody').html('');
		$('#depositbody').html('');
		$('#premises').html('');
		$('#shopsize').html('');
		$('#leasename').html('');
		$('#term').html('');
		$('#term').val('');
		$('#doo').html('');
		$('#doc').html('');		
	}   
   $('[id^="btnEdit"]').live('click', function() {
	        ////alert($(this).attr('val'));
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();		
		var $grouptenantmasid = $(this).attr('val');
		clearForm();
		var $a = $(this).attr('name');
		if($grouptenantmasid !="")
		{
			var url="load_report.php?item=documentstatus&itemval="+$grouptenantmasid;
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
                                            var i=0;
                                           $("#servicechrgbody").empty();
                                            $.each(data.myResult, function(i,response){
						$('#cc').html('');						
						var $tenantDetails = "Attn: "+response.cpname+"<br />"+response.leasename+"<br />"+response.poboxno+"-"+response.pincode+"<br />"+response.city;
						var $premises = response.premises;
						$('#tenantDetails').html($tenantDetails);
						$('#premises').html($premises);
						if(response.tradingname !="")
						   $('#leasename').html(response.tradingname+" ("+response.tenantcode+")");
						   else
						   $('#leasename').html(response.leasename+" ("+response.tenantcode+")");
                                                    $('#term').html(response.term);
                                                    $('#doc').html(response.doc);
						    $('#doo').html(response.expdt);
						    $('#building').html(response.buildingname);
						    $("#hidLeasename").val(response.leasename);
						    $("#hidgrouptenantmasid").val($a);
						    
						    //lease status
						    $('#leasestatus').val(response.leasestatus);
						    
						    //assign db values
						    $s = response.docmoddate;						    
						    if ($s ==null)
						    $s = response.doccrdate;
						    if ($s !=null)
						    $('#offlettdate').val($s)
						    
						    $s = response.leasemoddate;						    
						    if ($s ==null)
						    $s = response.leasecrdate;
						    if ($s !=null)
						    $('#leasedate').val($s)
						    
						    //offlet draft to tenant
						    $('#offletttotenantdate').val(response.offletttotenantdate);
						    $('#offlettretrundate').val(response.offlettretrundate);
						    
						    //lease draft to tenant
						    $('#leasetotenantdate').val(response.leasetotenantdate);
						    $('#leasereturndate').val(response.leasereturndate);
						    
						    // lease to land lord
						    $('#leasetolandlorddate').val(response.leasetolandlorddate);
						    
						    // lease to bank
						    $('#leasetobankdate').val(response.leasetobankdate);
						    $('#leasereturnfrombankdate').val(response.leasereturnfrombankdate);
						    
						    // lease for assesement
						    $('#leasetodutyassdate').val(response.leasetodutyassdate);
						    // assesement duty paid date
						    $('#leasedutypaiddate').val(response.leasedutypaiddate);
						    // lease return for assesement
						    $('#leasereturnfromassdate').val(response.leasereturnfromassdate);
						    
						    //lease final to tenant
						    $('#leasefinaltotenantdate').val(response.leasefinaltotenantdate);
						    $('#leasefinalreturndate').val(response.leasefinalreturndate);
						    
						    // leas final filed
						    $('#leasefileddate').val(response.leasefileddate);
						    
						     //is tenant pin or reg with us
						    $('#istenantpinno').val(response.istenantpinno);
						    $('#remarks').val(response.remarks);
						    
                                            });
					}
					else
					{
						//alert(response.msg);
						$("#cc").html(response.msg);
					}
				});             
                        });
		}		
	});
    $('#btnSave').click(function(){	
	var a = confirm("Can you confirm this ?");
	if (a== true)
	{
		var url="save_document_status.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
			$.each(data.error, function(i,response){
				if(response.s =="Success")
				{
					$('input[type=text]').val('');
					$('input[type=select]').val('');
					$("#cc").html(response.msg);
				}
				else
				{
					//alert(response.s);
					$("#cc").html(response.msg);
					
				}				
			});
		});
	}
	});	
    $('#btnView').click(function(){
	$('form').submit();
    });    
});
function showKeyCode(e) {
	//alert( "keyCode for the key pressed: " + e.keyCode + "\n" );
    if(e.keyCode == 112)
    {
	event.preventDefault();
	$("button:first").trigger('click');
    } else if(e.keyCode == 113)
    {	
	event.preventDefault();
	$("button:second").trigger('click');
    }
}
</script>		
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="GET">
<div id="container">
<center><h1>Document Status</h1></center><br>
<div id="menuDiv" width="100%" align="right">
<table>
<tr>        
        <td> <button class="buttonView" type="button" id="btnView"> View </button> </td>
</tr>
</table>
</div>

<br>
<div id="exampleDiv" width="100%">
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%" style="font-size: 11px;">
    <thead>
            <tr>
                   <th>Index</th>							
                    <th>Tenant</th>
		    <th>Building</th>
		    <th>Shop</th>		    
		    <th>Remarks</th>
		    <th>Edit</th>		    
            </tr>
    </thead>
    <tbody id="tbodyContent">
	<?php
	$sql = "select a.grouptenantmasid , b.leasename ,b.tradingname, e.shortname,c.shopcode , c.size,
		DATE_FORMAT(b.doc,'%d-%m-%Y') as doc,d.age,
		DATE_FORMAT( DATE_ADD(DATE_ADD(b.doc,interval @t1:=d.age year),interval -1 day), '%d-%m-%Y' ) AS 'expdt',g.remarks
		from 
		rpt_offerletter a
		inner join group_tenant_mas o on o.grouptenantmasid = a.grouptenantmasid
		inner join mas_tenant b on b.tenantmasid = o.tenantmasid
		inner join mas_shop c on c.shopmasid = b.shopmasid
		inner join mas_age d on d.agemasid = b.agemasidlt
		inner join mas_building e on e.buildingmasid = c.buildingmasid
		inner join mas_tenant_cp f on f.tenantmasid = b.tenantmasid
		left join trans_document_status g on g.grouptenantmasid= a.grouptenantmasid
		where b.companymasid =$companymasid 
		group by grouptenantmasid;";
	    
			$result=mysql_query($sql);
			
			if($result != null) // if $result <> false
			{
				if (mysql_num_rows($result) > 0)
				{
					$i=1;
					while ($row = mysql_fetch_assoc($result))
						{
						    $grouptenantmasid = $row['grouptenantmasid'];
						    
						     if($row['tradingname'] !="")
						     $row['leasename'] .= " T/A ".$row['tradingname'];						
						     $tr =  "<tr align='center'>
						     <td class='center'>".$i++."</td>
						     <td align='left'>".$row['leasename']."</td>
						     <td>".$row['shortname']."</td>
						     <td>".$row['shopcode'].",".$row['size']."</td>
						     <td align='justify'>".$row['remarks']."</td>";						     
						     $tr .="<td align='center'>
								<button type='button' id=btnEdit$i name='".$grouptenantmasid."'  val='".$grouptenantmasid."'>Edit</button>								
							</td>";
						     
						     echo $tr;
						}
				}
			}
?>
</tbody>
    <tfoot>
            <tr>
                   <th>Index</th>							
                    <th>Tenant</th>
		    <th>Building</th>
		    <th>Shop</th>		    
		    <th>Remarks</th>
		    <th>Edit</th>	    	    
            </tr>
    </tfoot>
</table>
</div>
<div id="details">
<div id="dataManipDiv">
<table id="usertbl" class="table2" width='100%'>
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan=4>
				Document Status
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selecTenant">
		<td>
			Tenant: 
		</td>
		<td id="leasename" bgcolor='#ffffae'>
			
		</td>	
		<td>
			Date:
		</td>
                <td colspan=3>
			<?php echo date("d-F-Y");?>
			<input type="hidden" id="hidLeasename" name="hidLeasename" value="">
		</td>
	</tr>
	<tr>
		<td>
			Land Lord:
		</td>
		<td colspan='3'>
			<?php echo "<b>".strtoupper($_SESSION['mycompany']); ?>
		</td>               
	</tr>
	<tr>
		<td>
			Tenant Address:
		</td>
		<td id="tenantDetails">
			
		</td>
		<td>
			Premises:
		</td>
		<td id=premises bgcolor='#ffffae'>
			
		</td>
	</tr>
        <tr>
                <td>
			Term:
		</td>
		<td id=term>
			
		</td>
		<td>
			Building:
		</td>
		<td id=building>
			
		</td>
	</tr>
        <tr>
		<td>
			Date of commencement:
		</td>
		<td id=doc>
			
		</td>
                <td>
			Expiry Date:
		</td>
		<td id=doo>
			
		</td>		
	</tr>
	<tr>
		<td colspan='4'>
			<b><u>Status Entry:</u></b>&nbsp;&nbsp;&nbsp;
			<select id='leasestatus' name='leasestatus'>
				<option value='0' selected>Offer Letter Generated</option>
				<option value='1'>Offer Sent To Tenant</option>
				<option value='2'>Lease Generated</option>
				<option value='3'>Lease Sent To Tenant</option>
				<option value='4'>Lease Sent To Landlord</option>
				<option value='5'>Lease Sent To Bank</option>
				<option value='6'>Lease Sent For Assessment</option>
				<option value='7'>Final Copy Sent to Tenant</option>
				<option value='8'>Lease Filed</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan='4'>
			<table id="table-3">
				<tr>
					<td>Offer Letter Generated On:</td>
					<td><input type='text' id='offlettdate' name='offlettdate' readonly style='background-color: #e1e1e1;'/></td>
				</tr>
				<tr>
					<td>Off Lett Sent to Tenant:</td>
					<td><input type='text' class='datepick' id='offletttotenantdate' name='offletttotenantdate' /></td>
					<td>Off Lett Return On:</td>
					<td><input type='text' class='datepick' id='offlettretrundate' name='offlettretrundate' /></td>
				</tr>
				<tr>
					<td style="color:blue">Lease Generated On:</td>
					<td style="color:blue"><input type='text' id='leasedate' name='leasedate' readonly style='background-color: #e1e1e1;'/></td>
				</tr>
				<tr>
					<td style="color:blue">Lease Sent to Tenant:</td>
					<td><input type='text' class='datepick' id='leasetotenantdate' name='leasetotenantdate' /></td>
					<td style="color:blue">Lease Return From Tenant On:</td>
					<td><input type='text' class='datepick' id='leasereturndate' name='leasereturndate' /></td>
				</tr>
				<tr>
					<td style="color:#ff8040">Lease Signed By Landlord On:</td>
					<td style="color:#ff8040"><input type='text' class='datepick' id='leasetolandlorddate' name='leasetolandlorddate' /></td>
				</tr>
				<tr>
					<td style="color:#ff8040">Lease Sent to Bank:</td>
					<td><input type='text' class='datepick' id='leasetobankdate' name='leasetobankdate' /></td>
					<td style="color:#ff8040">Lease Return From Bank On:</td>
					<td><input type='text' class='datepick' id='leasereturnfrombankdate' name='leasereturnfrombankdate' /></td>
				</tr>
				<tr>
					<td style="color:#000080">Lease Sent For Assessment On:</td>
					<td style="color:#000080"><input type='text' class='datepick' id='leasetodutyassdate' name='leasetodutyassdate' /></td>
				</tr>
				<tr>
					<td style="color:#000080">Duty Paid On:</td>
					<td><input type='text'  class='datepick' id='leasedutypaiddate' name='leasedutypaiddate' /></td>
					<td style="color:#000080">Lease Return From Assessment On:</td>
					<td><input type='text' class='datepick' id='leasereturnfromassdate' name='leasereturnfromassdate' /></td>
				</tr>				
				<tr>
					<td style="color:#ff0000">Lease Final Copy to Tenant On:</td>
					<td><input type='text' class='datepick' id='leasefinaltotenantdate' name='leasefinaltotenantdate' /></td>
					<td style="color:#ff0000">Lease Final Copy Return On</td>
					<td><input type='text' class='datepick' id='leasefinalreturndate' name='leasefinalreturndate' /></td>
				</tr>
				<tr>
					<td style="color:#008000">Is Tenant Pin / Reg with us:</td>
					<td>
						<select id="istenantpinno" name="istenantpinno">
							<option value='0' selected>No</option>
							<option value='1'>Yes</option>
						</select>
					</td>
					<td style="color:#008000">Lease Filed On:</td>
					<td><input type='text' class='datepick' id='leasefileddate' name='leasefileddate' /></td>
				</tr>
				<tr>
					<td style="color:#800040">Remarks</td>
					<td style="color:#800040">
						<textarea id='remarks' name='remarks' cols='50' rows='5'></textarea>
					</td>
				</tr>
			</table>			
		</td>               
	</tr>	
	<tr>		
		<td colspan=4 align='center'>
			<button type="button" id="btnSave">Save</button>
			<input type="hidden" id="hidgrouptenantmasid" name="hidgrouptenantmasid" value='100' />
		</td>
	</tr>	
	</tbody>
</table>

</div>
</div> <!--Main Div-->
</form>
&nbsp;&nbsp;<font color=red><label id="cc"></label></font>
</body>
</html>
