<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Tenant Discharge</title>
        <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="../shopstable.css">
         <script src="../js/jquery-2.1.4.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>  
<?php
    session_start();
    if (! isset($_SESSION['myusername']) ){
            header("location:../index.php");
    }
    include('../config.php');
    include('../MasterRef_Folder.php');
   $coid = $_SESSION['mycompanymasid'];	
   //include('approvers.php');

    function loadTenant()
    {
        $companymasid = $_SESSION['mycompanymasid'];	
	$sql= "select a.grouptenantmasid,c.leasename,c.tradingname,
			d.shopcode from rpt_surrender_lease a
			inner join group_tenant_mas b on b.grouptenantmasid=a.grouptenantmasid
			inner join mas_tenant c on c.tenantmasid =  b.tenantmasid
			inner join mas_shop d on d.shopmasid = c.shopmasid
			where c.companymasid = $companymasid order by leasename;";

	$result = mysql_query($sql);
        if($result != null)
        {
		while($row = mysql_fetch_assoc($result))
                {
                        if($row['tradingname'] !="")
			echo("<option value=".$row['grouptenantmasid'].">".$row['tradingname']." (".$row['shopcode'].")</option>");
			else
			echo("<option value=".$row['grouptenantmasid'].">".$row['leasename']." (".$row['shopcode'].")</option>");		
                }
        }
    }
    include('approvers.php');
?>
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
//$(document).ready(function() {   
//        
//	(function($) {
//   $.fn.fixMe = function() {
//      return this.each(function() {
//         var $this = $(this),
//            $t_fixed;
//         function init() {
//          $this.wrap('<div class="dataManipDiv" />');
//          //  $this.wrap('<div class="exampleDiv" />');
//          //  $this.wrap('<div class="menuDiv" />');
//            $t_fixed = $this.clone();
//            $t_fixed.find("tbody").remove().end().addClass("fixed").insertBefore($this);
//            resizeFixed();
//         }
//         function resizeFixed() {
//            $t_fixed.find("th").each(function(index) {
//               $(this).css("width",$this.find("th").eq(index).outerWidth()+"px");
//            });
//         }
//         function scrollFixed() {
//            var offset = $(this).scrollTop(),
//            tableOffsetTop = $this.offset().top,
//            tableOffsetBottom = tableOffsetTop + $this.height() - $this.find("thead").height();
//            if(offset < tableOffsetTop || offset > tableOffsetBottom)
//               $t_fixed.hide();
//            else if(offset >= tableOffsetTop && offset <= tableOffsetBottom && $t_fixed.is(":hidden"))
//               $t_fixed.show();
//         }
//         $(window).resize(resizeFixed);
//         $(window).scroll(scrollFixed);
//         init();
//      });
//   };
//})(jQuery);
//
////$(document).ready(function(){
//   $("table").fixMe();
//   $(".up").click(function() {
//      $('.dataManipDiv').animate({
//      scrollTop: 0
//   }, 1000);
// });
//});
//     });
$(document).ready(function() {    
    $("#dataManipDiv").hide();
    oTable = $('#example').dataTable({
		"bJQueryUI": true,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
		//"sPaginationType": "full_numbers"
	});
    $("#nrodt").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"dd-mm-yy"
		});
   
	$("#vacatingdt").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat:"dd-mm-yy"
	});
	$("#chqdate").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat:"dd-mm-yy"
	});
    $('#btnNew').click(function(){
        $("#tblheader").css('background-color', '#fc9');
	$("#tblheader").text("Tenant Discharge");
        $("#exampleDiv").hide();
	$("#dataManipDiv").show();	
	$("#companyname")[0].focus();
	$('input[type=text]').val('');
	$('input[type=select]').val('');

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
	        
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();		
		var $tenantmasid = $(this).attr('val');
               // alert($tenantmasid);
		clearForm();
		var $a = $(this).attr('name');
		if($tenantmasid !="")
		{
			var url="load_tenant.php?item=detailsTenant&itemval="+$tenantmasid;
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
						   $('#leasename').html(response.tradingname+" ( "+response.tenancyrefcode+")");
						   else
						   $('#leasename').html(response.leasename+" ( "+response.tenancyrefcode+")");
						   
						    $("#hidLeasename").val(response.leasename);
                                                    $('#term').html(response.term);
                                                    $('#doc').html(response.doc);
						    $('#doo').html(response.expdt);
						    $('#building').html(response.buildingname);
						    
						    //operation data
						    $('#opmoddate').html(response.opmoddate);
						    $('#nrodt').val(response.nrodate);
						    $('#vacatingdt').val(response.vacatingdate);
						    $('#iskplc').val(response.iskplc);
                                                   // alert(response.iskplc);
						    $('#iskiwasco').val(response.iskiwasco);
						    $('#ispainted').val(response.ispainted);
						    $('#iselectrical').val(response.iselectrical);
						    $('#issubmeter').val(response.issubmeter);
						    $('#otherdetails').val(response.otherdetails);
						    $('#oplegal').val(response.oplegal);
						    $('#opremarks').val(response.opremarks);
						    $('#opapproval').val(response.opapproval);
						    
						    //accounts data
						    $('#acmoddate').html(response.acmoddate);						    
						    $('#outstandingpayment').val(response.outstandingpayment);
						    $('#asc').val(response.asc);
						    $('#submeterchrgs').val(response.submeterchrgs);
						    $('#baltilldt').val(response.baltilldt);
						    $('#securitydeposit').val(response.securitydeposit);
						    $('#payorrefund').val(response.payorrefund);
						    $('#chqno').val(response.chqno);
						    $('#chqdate').val(response.acchqdate);
						    $('#aclegal').val(response.aclegal);
						    $('#acremarks').val(response.acremarks);
						    $('#acdischargetype').val(response.acdischargetype);
						    $('#acapproval').val(response.acapproval);
						    $("#hidgrouptenantmasid").val($a);
                                            });
					}
					else
					{
						alert(response.msg);                                                
                                               //clearForm();
					}
				});             
                        });
		}
		else
		{		
			//clearForm();
		}
	});
    $('#btnSave').click(function(){
	//if(jQuery.trim($("#tenantmasid").val()) == "")
	//{
	//	alert("Please select Tenant");return false;
	//}
	var a = confirm("Can you confirm this ?");
	if (a== true)
	{
		var url="save_tenant_discharge.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
			$.each(data.error, function(i,response){
				if(response.s =="Success")
				{
					$('input[type=text]').val('');
					$('input[type=select]').val('');
					$("#cc").html(response.msg);
					alert(response.msg);
                    $('form').submit();
                                        
					
				}
				else
				{
					//alert(response.s);
					$("#cc").html(response.msg);
                    alert(response.msg);
					
				}				
			});
		});
	}
	});	
    $('#btnView').click(function(){
	$('form').submit();
    });    

   $('[id^="submeterchrgs"]').live('blur', function() {	
    var submetercharges=parseInt($('#submeterchrgs').val());
    var securitydeposit=parseInt($('#securitydeposit').val());
    var outstandingpayment=parseInt($('#outstandingpayment').val());
    
    var actualservicecharge=parseInt($('#asc').val());
    var total=submetercharges+outstandingpayment+actualservicecharge;
   
   $('#baltilldt').val(total);
    $('#netcollectable').val(total-securitydeposit);
 });  
$('[id^="asc"]').live('blur', function() {	
    var submetercharges=parseInt($('#submeterchrgs').val());
    var securitydeposit=parseInt($('#securitydeposit').val());
    var outstandingpayment=parseInt($('#outstandingpayment').val());
    
    var actualservicecharge=parseInt($('#asc').val());
    var total=submetercharges+outstandingpayment+actualservicecharge;
   
   $('#baltilldt').val(total);
   $('#netcollectable').val(total-securitydeposit);
 });  
$('[id^="outstandingpayment"]').live('blur', function() {	
    var submetercharges=parseInt($('#submeterchrgs').val());
    var securitydeposit=parseInt($('#securitydeposit').val());
    var outstandingpayment=parseInt($('#outstandingpayment').val());
    
    var actualservicecharge=parseInt($('#asc').val());
    var total=submetercharges+outstandingpayment+actualservicecharge;
   
   $('#baltilldt').val(total);
   $('#netcollectable').val(total-securitydeposit);
 });  
 $('[id^="securitydeposit"]').live('blur', function() {	
    var submetercharges=parseInt($('#submeterchrgs').val());
    var securitydeposit=parseInt($('#securitydeposit').val());
    var outstandingpayment=parseInt($('#outstandingpayment').val());
    
    var actualservicecharge=parseInt($('#asc').val());
    var total=submetercharges+outstandingpayment+actualservicecharge;
   
   $('#baltilldt').val(total);
   $('#netcollectable').val(total-securitydeposit);
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
<!--<div id="container">-->
<center><h1>Tenant Discharge</h1></center><br>
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
		    <th>Commence Dt</th>
                    <th>End Dt</th>
                    <th>Period</th>
		    <th>Term</th>		    
                    <th>Operations Mgr</th>
		    <th>Op Legal</th>
                    <th>Credit Cont</th>
		    <th>Ac Legal</th>
		    <th>Edit</th>		    
            </tr>
    </thead>
    <tbody id="tbodyContent">
        <!--//	$sql = "select a.rptsurrenderleasemasid,a.grouptenantmasid,c.leasename,c.tradingname,e.buildingname,k.oplegal,m.aclegal,-->
	<?php
        $sql = "select a.rptsurrenderleasemasid,a.grouptenantmasid,c.leasename,c.tradingname,e.buildingname,k.*,m.*,

		    DATE_FORMAT( c.doc, '%d-%m-%Y' ) AS doc, 
		    @t2:= DATE_ADD(c.doc,interval @t1:=f.age year) as a,									
			DATE_FORMAT( DATE_ADD(c.doc,interval @t1:=f.age year), '%d-%m-%Y' ) AS b,		   
			DATE_FORMAT( DATE_ADD(@t2,interval -1 day), '%d-%m-%Y' ) AS expdt,
			f.age AS term, f1.age AS period,d.shopcode,d.size,f1.shortdesc,e.shortname,			
			case
				  when k.opapproval like '0' then 'Occupied'
				  when k.opapproval like '1' then 'Vacant'
			end as opapproval,
			case
				  when m.acdischargetype like '0' then 'Partial'
				  when m.acdischargetype like '1' then 'Full'
			end as acdischargetype, 
			case
				  when m.acapproval like '0' then 'Occupied'
				  when m.acapproval like '1' then 'Vacant'
			end as acapproval
			
			from rpt_surrender_lease a
			inner join group_tenant_mas b on b.grouptenantmasid=a.grouptenantmasid
			inner join mas_tenant c on c.tenantmasid =  b.tenantmasid
			inner join mas_shop d on d.shopmasid = c.shopmasid
			inner join mas_building e on e.buildingmasid = d.buildingmasid
			
			inner join mas_age f ON f.agemasid = c.agemasidlt
			inner join mas_age f1 ON f1.agemasid = c.agemasidrc
			
			left outer join trans_tenant_discharge_op k on k.grouptenantmasid = a.grouptenantmasid
			left outer join trans_tenant_discharge_ac m on m.grouptenantmasid = a.grouptenantmasid

			where c.companymasid = $coid order by a.rptsurrenderleasemasid;";
			
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
						    $row['leasename'] .= " (t/a) ".$row['tradingname'];
						    
						     $leasename = $row["leasename"]."  - (".$row['shopcode']." , ". $row['size'].")";
						     $buildingname = $row["shortname"];
						     $doc = $row["doc"];
						     $expdt = $row["expdt"];
						     $term = $row["term"];
						     $period = $row["shortdesc"];
						     $opapproved = $row["opapproval"];
						     $acapproved = $row["acapproval"];
						     $tr =  "<tr>
						     <td class='center'>".$i++."</td>
						     <td>".$leasename."</td>
						     <td>".$doc."</td>
						     <td>".$expdt."</td>
						     <td>".$period."</td>
						     <td>".$term."</td>";
						     
						     if($opapproved == "Occupied")
						     $tr .="<td><font color='red'>".$opapproved."</font></td>";
						     else
						     $tr .="<td><font color='green'>".$opapproved."</font></td>";
						     
						     if($row['oplegal'] == "1")
							$tr .="<td bgcolor='red' style='color:white;font-face:bold;'> Yes </td>";
						     else
							$tr .="<td> NO </td> ";
						     
						     if($row['acdischargetype'] == "Partial")
						     $acdischtype ="<font color='red'>".$row['acdischargetype']."</font>";
						     else
						     $acdischtype ="<font color='green'>".$row['acdischargetype']."</font>";
						     
						     if($acapproved == "Occupied")
						     $tr .="<td><font color='red'>".$acapproved."</font></td>";
						     else
						     $tr .="<td><font color='green'>".$acapproved."</font>, ".$acdischtype."</td>";
						     
						     if($row['aclegal'] == "1")
							$tr .="<td bgcolor='red' style='color:white;font-face:bold;'> Yes </td>";
						     else
							$tr .="<td> NO </td> ";
						     
						     $tr .="<td>
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
		    <th>Commence Dt</th>
                    <th>End Dt</th>
                    <th>Period</th>
		    <th>Term</th>		    
                    <th>Operation</th>
		    <th>Op Legal</th>		    
                    <th>Finance</th>
		    <th>Ac Legal</th>		    
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
				Tenant Discharge
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selecTenant">
		<td>
			Tenant: 
		</td>
		<td id="leasename" bgcolor='yellow'>
			
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
		<td id=premises bgcolor='white'>
			
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
		<td colspan='2'>
			<b><u>OPERATIONS MANAGER INFO:</u></b>
		</td>
                <td colspan='2'>			
			<b><u>CREDIT CONTROLLER INFO:</u></b>
		</td>		
	</tr>
	<tr>
		<td colspan='2'>
			<?php
			  $user =  strtolower($_SESSION['myusername']);
			  if (!array_key_exists($user, $search_array_op)&& !array_key_exists($user, $search_array_admin)) {	
			 //if ($user == "muthu"){				
			?>
			<script type="text/javascript" language="javascript">
				$(document).ready(function() {
                                  
					$('#operationdiv').fadeTo('slow',.6);
					$('#operationdiv :input').attr('disabled', true);
				});
			</script>
			<?php
			  }
			?>
			<div id='operationdiv'>
			<table width='100%'>
				<tr>
					<td align='right' width='50%'>
						Last Modified Date:			
					</td>
					<td width='50%' id='opmoddate' style='color: red;'>
						
					</td>
				</tr>
				<tr>
					<td align='right' width='50%'>
						Notice Received On:
					</td>
					<td width='50%'>
						<input type="text" id="nrodt" name="nrodt" style='width: 120px'>
					</td>
				</tr>
				<tr>
					<td align='right'>
						Vacating Dt:
					</td>
					<td>
						<input type="text" id="vacatingdt" name="vacatingdt" style='width: 120px'>
					</td>
				</tr>
				<tr>
					<td align='right'>
						K P L C bill cleared.
					</td>
					<td>
						<select id='iskplc' name='iskplc' style='width: 120px'>
							<option selected='selected' value='0'>SELECT</option>
							<option value='1'>YES</option>
							<option value='2'>NO</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align='right'>
						KIWASCO bill cleared:
					</td>
					<td>
						<select id='iskiwasco' name='iskiwasco' style='width: 120px'>
							<option selected='selected' value='0'>SELECT</option>
							<option value='1'>YES</option>
							<option value='2'>NO</option>
						</select>
					</td>
				</tr><tr>
					<td align='right'>
						Space reinstated / painted with 2 coats of soft white paint:
					</td>
					<td>
						<select id='ispainted' name='ispainted' style='width: 120px'>
							<option selected='selected' value='0'>SELECT</option>
							<option value='1'>YES</option>
							<option value='2'>NO</option>
						</select>
					</td>
				</tr><tr>
					<td align='right'>
						Electrical / glass / floor tiles fittings are intact:
					</td>
					<td>
						<select id='iselectrical' name='iselectrical' style='width: 120px'>
							<option selected='selected' value='0'>SELECT</option>
							<option value='1'>YES</option>
							<option value='2'>NO</option>
						</select>
					</td>
				</tr><tr>
					<td align='right'>
						Sub meters are in order:
					</td>
					<td>
						<select id='issubmeter' name='issubmeter' style='width: 120px'>
							<option selected='selected' value='0'>SELECT</option>
							<option value='1'>YES</option>
							<option value='2'>NO</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align='right'>
						If any of above not in order provide details:
					</td>
					<td>
						<textarea cols='25' rows='5' id='otherdetails' name='otherdetails'>
							
						</textarea>
					</td>
				</tr><tr>
					<td align='right'>
						Is Legal case:
					</td>
					<td>
						<select id='oplegal' name='oplegal' style='width: 120px'>
							<option selected='selected' value='0'>NO</option>
							<option value='1'>YES</option>							
						</select>
					</td>
				</tr><tr>
					<td align='right'>
						Remarks:
					</td>
					<td>
						<textarea cols='25' rows='5' id='opremarks' name='opremarks'>
							
						</textarea>
					</td>
				</tr><tr>
					<td align='right'>
						OPERATIONS MANAGER - APPROVAL:
					</td>
					<td>
						<select id='opapproval' name='opapproval' style='width: 120px'>
							<option elected='selected' value='0'>Occupied</option>
							<option value='1'>Vacant</option>							
						</select>
					</td>
				</tr>
			</table>
			</div>			
		</td>
                <td colspan='2' valign='top'>
			

			<?php
			  $user =  strtolower($_SESSION['myusername']);
			  //echo $user;
			  //print_r($search_array_ac);
			 // print_r($search_array_op);
			  //print_r($search_array_admin);
			  if (!array_key_exists($user, $search_array_ac)&& !array_key_exists($user, $search_array_admin)) {	
                 // echo "does not exist so "			  
			?>
			<script type="text/javascript" language="javascript">
				$(document).ready(function() {
					$('#accountdiv').fadeTo('slow',.6);
					$('#accountdiv :input').attr('disabled', true);					
				});
			</script>
			<?php
			  }
			?>
			<div id='accountdiv'>
			<table width='100%'>
				<tr>
					<td align='right' width='50%'>
						Last Modified Date:					
					</td>
					<td width='50%' id='acmoddate' style='color: red;'>
						
					</td>
				</tr>
				<tr>
					<td align='right'>
						Outstanding in account
					</td>
					<td>
						<input type="text" id="outstandingpayment" name="outstandingpayment" style='width: 120px'>
					</td>
				</tr><tr>
					<td align='right'>
						A S C Prorata till date 
					</td>
					<td>
						<input type="text" id="asc" name="asc" style='width: 120px'>
					</td>
				</tr><tr>
					<td align='right'>
						Water & Elec charges (Sub meter)
					</td>
					<td>
						<input type="text" id="submeterchrgs" name="submeterchrgs" style='width: 120px'>
					</td>
				</tr><tr>
					<td align='right'>
						Balance till date.
					</td>
					<td>
						<input type="text" id="baltilldt" name="baltilldt" style='width: 120px'>
					</td>
				</tr><tr>
					<td align='right'>
						Security deposit held
					</td>
					<td>
						<input type="text" id="securitydeposit" name="securitydeposit" style='width: 120px'>
					</td>
				</tr><tr>
					<td align='right'>
						Balance collectable / refundable
					</td>
					<td>
						<select id='payorrefund' name='payorrefund' style='width: 120px'>
							<option selected='selected' value='0'>Select</option>
							<option  value='collectable'>Collectable</option>
							<option value='refundable'>Refundable</option>							
						</select> <span>  Net Collectable</span>
					</td>
                                        <td>
						<input type="text" id="netcollectable"  style='width: 120px'>
					</td>
				</tr><tr>
					<td align='right'>
						Paid BY Check No
					</td>
					<td>
						<input type="text" id="chqno" name="chqno" style='width: 120px'>
					</td>
				</tr><tr>
					<td align='right'>
						Check Date
					</td>
					<td>
						<input type="text" id="chqdate" name="chqdate" style='width: 120px'>
					</td>
				</tr><tr>
					<td align='right'>
						Is Legal case:
					</td>
					<td>
						<select id='aclegal' name='aclegal' style='width: 120px'>
							<option selected='selected' value='0'>NO</option>
							<option value='1'>YES</option>							
						</select>
					</td>
				</tr><tr>
					<td align='right'>
						Remarks:
					</td>
					<td>
						<textarea cols='25' rows='5' id='acremarks' name='acremarks'>
							
						</textarea>
					</td>
				</tr><tr>
					<td align='right'>
						Discharge Type:
					</td>
					<td>
						<select id='acdischargetype' name='acdischargetype' style='width: 120px'>
							<option selected='selected' value='0'>Partial</option>
							<option value='1'>Full</option>							
						</select>
					</td>
				</tr><tr>
					<td align='right'>
						CREDIT CONTROLLER - APPROVAL:
					</td>
					<td>
						<select id='acapproval' name='acapproval' style='width: 120px'>
							<option selected='selected' value='0'>Occupied</option>
							<option value='1'>Vacant</option>							
						</select>
					</td>
				</tr>
			</table>
			</div>			
		</td>
	</tr>
	<?php			  
		if (array_key_exists($user, $search_array_op) || array_key_exists($user, $search_array_ac)|| array_key_exists($user, $search_array_admin)) {			  
	?>
		<tr>		
			<td colspan=4 align='center'>
				<button type="button" id="btnSave">Save</button>
				<input type="hidden" id="hidgrouptenantmasid" name="hidgrouptenantmasid" value='100' />
			</td>
		</tr>
	<?php
		}
	?>
	</tbody>
</table>

</div>
</div> <!--Main Div-->
</form>
&nbsp;&nbsp;<font color=red><label id="cc"></label></font>
</body>
</html>
