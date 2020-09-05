<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Rectification Tenant</title>
<?php
	session_start();
	if (! isset($_SESSION['myusername']) ){
		header("location:../index.php");
	}
	include('../config.php');
	include('../MasterRef_Folder.php');
	function loadCptype()
	{
		$sql = "select cptype, cptypemasid from mas_cptype";
		$result = mysql_query($sql);
		if($result != null)
		{
			while($row = mysql_fetch_assoc($result))
			{
				echo("<option value=".$row['cptypemasid'].">".$row['cptype']."</option>");		
			}
		}
	}
	function loadAgeMasterLT()
		{
			$sql = "select age, agemasid from mas_age where active =1 and age not like 'Per%' and age not like 'per%'";
			$result = mysql_query($sql);
			if($result != null)
			{
				while($row = mysql_fetch_assoc($result))
				{
					echo("<option value=".$row['agemasid'].">".$row['age']."</option>");		
				}
			}
		}
	function loadAgeMasterRc()
	{
		$sql = "select age, agemasid from mas_age where active =1 and age like 'Per%' and age like 'per%'";
		$result = mysql_query($sql);
		if($result != null)
		{
			while($row = mysql_fetch_assoc($result))
			{
				echo("<option value=".$row['agemasid'].">".$row['age']."</option>");		
			}
		}
	}
	function loadShoptype()
	{
		$sql = "select shoptype, shoptypemasid from mas_shoptype where active =1";
		$result = mysql_query($sql);
		if($result != null)
		{
			while($row = mysql_fetch_assoc($result))
			{
				echo("<option value=".$row['shoptypemasid'].">".$row['shoptype']."</option>");		
			}
		}
	}
	function loadOrgtype()
	{
		$sql = "select orgtype, orgtypemasid from mas_orgtype where active =1";
		$result = mysql_query($sql);
		if($result != null)
		{
			while($row = mysql_fetch_assoc($result))
			{
				echo("<option value=".$row['orgtypemasid'].">".$row['orgtype']."</option>");		
			}
		}
	}
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {

    $('#tradingnamerow').hide();
    $('#dataManipDiv').hide();
	oTable = $('#example').dataTable({
		"bJQueryUI": true,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
		//"sPaginationType": "full_numbers"			
	});
        $('#tenantname').blur(function() {
            $(this).val($(this).val().toUpperCase());
        });
	$('#btnNew').click(function(){
		clearDynTable();
		
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("New Tenant Rectification");
		
		$("#tblHeaderCp").css('background-color', '#fc9');
		$("#tblHeaderCp").text("Rectification Tenant Edit");
		
		$("#tblheaderCp1").css('background-color', '#fc9');
		$("#tblheaderCp2").css('background-color', '#fc9');
		$("#tblheaderCp3").css('background-color', '#fc9');
		
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#tenantcpDiv").show();		
		$("#rectenantmasid").hide();
		$("#editTr").hide()
		$("#newTr").show();		
		$('input[type=text]').val('');
		$('#tenanttypemasid').val('0');                
		$('#shoptypemasid').val('0');
		$('#orgtypemasid').val('0');
		$('#agemasidrc').val('0');
		loadtenant();
		
	});
	function loadtenant()
	{
		var url="load_rec_tenant.php?item=loadRecTenant";					
		$.getJSON(url,function(data){
			$.each(data.error, function(i,response){
				if(response.s == "Success")
				{
                                        $('#tenantmasid').empty();
					$('#tenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
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
							
                                                $('#tenantmasid').append( new Option(a,response.tenantmasid,true,false) );
					});
				}
				else
				{
					alert(response.s);
				}
			});		
		});
		$("#tenantmasid").show().focus();
	}
	$('#btnEdit').click(function(){
		clearDynTable();
		
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("New Tenant Rectification");
		
		$("#tblHeaderCp").css('background-color', '#4ac0d5');
		$("#tblHeaderCp").text("Rectification Tenant Edit");
		
		$("#tblheaderCp1").css('background-color', '#fc9');
		$("#tblheaderCp2").css('background-color', '#fc9');
		$("#tblheaderCp3").css('background-color', '#fc9');
		
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#tenantcpDiv").show();
		$("#tenantmasid").hide();		
		$("#editTr").show()
		$("#newTr").hide();		
		$('input[type=text]').val('');
		$('#tenanttypemasid').val('0');                
		$('#shoptypemasid').val('0');
		$('#orgtypemasid').val('0');
		$('#agemasidrc').val('0');
		editRecTenant();
		
	});
	function editRecTenant()
	{
		var url="load_rec_tenant.php?item=editRecTenant";					
		$.getJSON(url,function(data){
			$.each(data.error, function(i,response){
				if(response.s == "Success")
				{
                                        $('#rectenantmasid').empty();
					$('#rectenantmasid').append( new Option("-----Select Tenant-----","",true,false) );
					$.each(data.myResult, function(i,response){
						var a = response.leasename+"("+response.shopcode+")";
                                                $('#rectenantmasid').append( new Option(a,response.tenantmasid,true,false) );
					});
				}
				else
				{
					alert(response.s);
				}
			});		
		});
		
		$("#rectenantmasid").show().focus();
	}	
	$('#btnSave').click(function(){
		if(jQuery.trim($("#tenantmasid").val()) == "")
		{
			alert("Please select Tenant");return false;
		}
		if(jQuery.trim($("#newleasename").val()) == "")
		{
			alert("Please enter lease name");return false;
		}
		if(jQuery.trim($("#shoptypemasid").val()) == "")
		{
			alert("Please select Shop type");return false;
		}		
		if(jQuery.trim($("#orgtypemasid").val()) == "")
		{
			alert("Please select Org type");return false;
		}
		if(jQuery.trim($("#nob").val()) == "")
		{
			alert("Please enter Nature of Business");return false;
		}		
		if(jQuery.trim($("#agemasidrc").val()) == "")
		{
			alert("Please select Rent cycle");return false;
		}
		var r=confirm("Can you confirm this?");
		if (r == true)
		{
			var url="save_rec_tenant.php?action=Save";
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
					$.each(data.error, function(i,response){
						if(response.s =="Success")
						{
							$('input[type=text]').val('');
							$('#shoptypemasid').val('');
							$('#orgtypemasid').val('');						
							$('#agemasidrc').val('');
							//$('#agemasidlt').val('');
							$('#tenanttypemasid').val('');						
							clearDynTable();
							loadtenant();
							$('#cc').html(response.msg);
						}
						else
						{
							//alert(response.msg);							
							$('#cc').html(response.msg);
						}						
					});
			});
		}
		else
		{
			return false;
		}
	});
	$('#btnUpdate').click(function(){
		if(jQuery.trim($("#rectenantmasid").val()) == "")
		{
			alert("Please select Tenant");return false;
		}
		if(jQuery.trim($("#newleasename").val()) == "")
		{
			alert("Please enter lease name");return false;
		}
		if(jQuery.trim($("#shoptypemasid").val()) == "")
		{
			alert("Please select Shop type");return false;
		}		
		if(jQuery.trim($("#orgtypemasid").val()) == "")
		{
			alert("Please select Org type");return false;
		}
		if(jQuery.trim($("#nob").val()) == "")
		{
			alert("Please enter Nature of Business");return false;
		}		
		if(jQuery.trim($("#agemasidrc").val()) == "")
		{
			alert("Please select Rent cycle");return false;
		}
		var r=confirm("Can you confirm this?");
		if (r == true)
		{
			var url="save_rec_tenant.php?action=Update";
			var dataToBeSent = $("form").serialize();
			$.getJSON(url,dataToBeSent, function(data){
					$.each(data.error, function(i,response){
						if(response.s =="Success")
						{
							$('input[type=text]').val('');
							$('#shoptypemasid').val('');
							$('#orgtypemasid').val('');						
							$('#agemasidrc').val('');
							//$('#agemasidlt').val('');
							$('#tenanttypemasid').val('');						
							clearDynTable();
							editRecTenant()
							$('#cc').html(response.msg);
						}
						else
						{
							//alert(response.msg);
							$('#cc').html(response.msg);
						}
					});
			});
		}
		else
		{
			return false;
		}
	});	
	$('#btnView').click(function(){
		$('form').submit();
	});
	$('#orgtypemasid').change(function(){
		var a = $('#orgtypemasid').val();
		if(a ==2){
			$('#tradingnamerow').show();
		}
		else{
			$('#tradingnamerow').hide();
			$('#tradingname').val("");
		}
	});
	$("#tenantmasid").change(function(){
		clearDynTable();
		$("#newleasename").focus();
		var $tenantmasid = $('#tenantmasid').val();
		if($tenantmasid !="")
		{
			var url="load_rec_tenant.php?item=details&itemval="+$tenantmasid;
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
                                            $.each(data.myResult, function(i,response){
						    $("#tenanttypemasid").val(response.tenanttypemasid);                                                   
						    $("#shoptypemasid").val(response.shoptypemasid);
						    $("#orgtypemasid").val(response.orgtypemasid);
						    $("#leasename").val(response.leasename);
						    $("#newleasename").val(response.leasename);
						    var z=$("#orgtypemasid").val();
						    if(z==2){
							$("#tradingname").val(response.tradingname);
							$("#tradingnamerow").show();
						    }
						    else
						    {
							$("#tradingname").val('');
							$("#tradingnamerow").hide();
						    }
						    $("#nob").val(response.nob);						    
						    $("#agemasidrc").val(response.agemasidrc);
						    //$("#agemasidlt").val(response.agemasidlt);
							var url="load_rec_tenant.php?item=detailsCP&itemval="+$tenantmasid;
							$.getJSON(url,function(data){
								$.each(data.error, function(i,response){
									if(response.s == "Success")
									{
										$('#cprowbody').append(response.msg);
									}
								});
							});						  
                                            });
					   
					}
					else
					{
						alert(response.s);
                                                $('input[type=text]').val('');
						//$('#agemasidlt').val('');
						$('#agemasidrc').val('');						
					}
				});             
                        });
		}
	});
	$("#rectenantmasid").change(function(){
		clearDynTable();
		$("#newleasename").focus();
		var $rectenantmasid = $('#rectenantmasid').val();
		if($rectenantmasid !="")
		{
			var url="load_rec_tenant.php?item=detailsedit&itemval="+$rectenantmasid;
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
                                            $.each(data.myResult, function(i,response){
						    $("#tenanttypemasid").val(response.tenanttypemasid);                                                   
						    $("#shoptypemasid").val(response.shoptypemasid);
						    $("#orgtypemasid").val(response.orgtypemasid);						    
						    $("#newleasename").val(response.leasename);
						    var z=$("#orgtypemasid").val();
						    if(z==2){
							$("#tradingname").val(response.tradingname);
							$("#tradingnamerow").show();
						    }
						    else
						    {
							$("#tradingname").val('');
							$("#tradingnamerow").hide();
						    }
						    $("#nob").val(response.nob);						    
						    $("#agemasidrc").val(response.agemasidrc);
						    //$("#agemasidlt").val(response.agemasidlt);
							var url="load_rec_tenant.php?item=detailseditcp&itemval="+$rectenantmasid;
							$.getJSON(url,function(data){
								$.each(data.error, function(i,response){
									if(response.s == "Success")
									{
										$('#cprowbody').append(response.msg);
									}
								});
							});						  
                                            });
					   
					}
					else
					{
						alert(response.s);
                                                $('input[type=text]').val('');						
						//$('#agemasidlt').val('');
						$('#agemasidrc').val('');						
					}
				});             
                        });
		}
	});
	function clearDynTable()
	{
	       $('#dynTable tr:gt(1)').remove();
	       //$tbl  = "<tr class='prototype'><td><input type='radio' style='width: 150px;' name='documentname' checked/></td>";
	       //$tbl += "<td><input type='text' style='width: 150px;' name='cpname' /></td>";
	       //$tbl += "<td><select style='width: 150px;' name='cptypemasid'><option value='' selected>----Contact Designation----</option><?php loadCptype();?></select>";
	       //$tbl += "<td><input type='text' style='width: 150px;' name='cpnid' /></td>";
	       //$tbl += "<td><input type='text' style='width: 150px;' name='cpmobile' /></td>";
	       //$tbl += "<td><input type='text' style='width: 150px;' name='cplandline' /></td>";
	       //$tbl += "<td><input type='text' style='width: 150px;' name='cpemailid' /></td>";
	       //$tbl += "<td><button type='button' class='remove'>Remove</button></td><tr>";
	       //$('#cprowbody').append($tbl);
	       //$('#cc').html('');
	}
});
</script>
</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>Rectification Tenant</h1>
<div id="menuDiv" width="100%" align="right">
    <table>
                    <tr>
                            <td> <button class="buttonNew" type="button" id="btnNew"> New </button> </td>
                            <td> <button class="buttonEdit" type="button" id="btnEdit"> Edit </button> </td>
                            <td> <button class="buttonView" type="button" id="btnView"> View </button> </td>
                    </tr>
    </table>
</div>
    <br>
<div id="exampleDiv" width="100%">
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
	<thead>
		<tr>
			<th>Index</th>							
			<th>Building</th>
			<th>Tenant</th>
			<th>Shop</th>
			<th>Created</th>
			<th>Date</th>
			<th>Modified</th>
			<th>Date</th>
		</tr>
	</thead>
	<tbody id="tbodyContent">
	<?php
	$companymasid  = $_SESSION['mycompanymasid'];
	$companyname = $_SESSION['mycompany'];
	$sql = "select a.*,b.companyname,c.buildingname,c.shortname,d.shopcode\n"
		. "from rec_tenant a\n"
		. "inner join mas_company b on a.companymasid = b.companymasid\n"
		. "inner join mas_building c on a.buildingmasid = c.buildingmasid\n"
		. "inner join mas_shop d on a.shopmasid = d.shopmasid where a.companymasid=$companymasid";
	$result=mysql_query($sql);
	if($result != null) // if $result <> false
	{
		if (mysql_num_rows($result) > 0)
		{
			$i=1;
			   while ($row = mysql_fetch_assoc($result))
				   {
					$buildingname = $row["shortname"];
					$shopcode = $row["shopcode"];
					$leasename = $row["leasename"]."(".$row["tenantcode"].")";
					
					$cby = $row["createdby"];
					$cdt = $row["createddatetime"];
					if(strtotime($cdt) != 0)
					{
						//$cdt = date_format(new DateTime($cdt), "D d-F-Y H:i:s");
						$cdt = date_format(new DateTime($cdt), "d-m-Y");
						//E.g. Fri 03-August-2012 13:51:37
					}
					else
					{
						$cdt="";
					}
					$mby = $row["modifiedby"];
					$mdt = $row["modifieddatetime"];
					if(strtotime($mdt) != 0)
					{
						//$mdt = date_format(new DateTime($mdt), "D d-F-Y H:i:s");
						$mdt = date_format(new DateTime($mdt), "d-m-Y");
					}
					else
					{
						$mdt = "";
					}
					$tr =  "<tr>
					<td class='center'>".$i++."</td>
					<td>".$buildingname."</td>
					<td>".$leasename."</td>
					<td>".$shopcode."</td>
					<td>".$cby."</td>
					<td>".$cdt."</td>
					<td>".$mby."</td>
					<td>".$mdt."</td>
					";
					echo $tr;
				}
		}
	}		
?>
	</tbody>
	<tfoot>
		<tr>
			<th>Index</th>							
			<th>Building</th>
			<th>Tenant</th>
			<th>Shop</th>
			<th>Created</th>
			<th>Date</th>
			<th>Modified</th>
			<th>Date</th>
		</tr>
	</tfoot>
</table>
</div>
<div id="dataManipDiv">
	<table id="usertbl" class="table2" width='60%'>
		<thead>
			<tr>
				<th id="tblheader" align="left" colspan=4>
					Rectification of Lease
				</th>
			</tr>
		</thead>
		<tbody>
		<tr id="selecTenant">
			<td>
				Select Tenant: <font color="red">*</font>
			</td>
			<td>
				<select id="rectenantmasid" name="rectenantmasid">
					<option value="" selected>--Select Modified Tenant--</option>
				</select>
				<select id="tenantmasid" name="tenantmasid">
					<option value="" selected>--Select Tenant--</option>
				</select>
			</td>			
		</tr>
		<tr>
			<td>
				New Lease Name<font color="red">*</font>
			</td>
			<td>
				<input type="text" id="newleasename" name="newleasename">
			</td>
		</tr>
		<tr>
		<td>
			Select Shoptype <font color="red">*</font>
		</td>
		<td>
			<select id="shoptypemasid" name="shoptypemasid">
				<option value="" selected>----Select Shoptype----</option>
				<?php loadShoptype();?>
			</select>
		</td>
		</tr>
		<tr id="selectOrgtype">
			<td>
				Select Orgtype <font color="red">*</font>
			</td>
			<td>
				<select id="orgtypemasid" name="orgtypemasid">
					<option value="" selected>----Select Orgtype----</option>
					<?php loadOrgtype();?>
				</select>
			</td>
		</tr>
		<tr id='tradingnamerow'>
		<td>
			Trading Name
		</td>
		<td>
			<input type="text" id="tradingname" name="tradingname">
		</td>
		</tr>		
		<tr>
			<td>
				Nature of Business <font color="red">*</font>
			</td>
			<td>
				<input type="text" id="nob" name="nob">
			</td>
		</tr>
		<!--<tr>
			<td>
				Lease Term<font color="red">*</font>
			</td>
			<td>
				<select id="agemasidlt" name="agemasidlt">
					<option value="" selected>----Lease Term----</option>
					<?php loadAgeMasterLT();?>
				</select>
			</td>
		</tr>-->
		<tr>
		<td>
			Rent Cycle<font color="red">*</font>
		</td>
		<td>
			<select id="agemasidrc" name="agemasidrc">
				<option value="" selected>----Rent Cycle----</option>
				<?php loadAgeMasterRc();?>
			</select>
		</td>
		</tr>
	<tr id="newTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnSave">Save </button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update</button>
		</td>
	</tr>
		</tbody>
	</table>
	<script>
		$(document).ready(function() {
			var id = 0;
			var i = 0;
			// Add button functionality
			$("table.dynatable button.add").click(function() {
				id++;
				i++;
				var master = $(this).parents("table.dynatable");
				
				// Get a new row based on the prototype row
				var prot = master.find(".prototype").clone();
				prot.find("input[type='text']").each(function() {
					var a = $(this).attr('name');
					$(this).attr('name',a+i);
					$(this).attr('value',a+i);
				});
				prot.find("input[type='radio']").each(function() {
					$(this).attr('checked',true);
				});
				prot.find("select").each(function() {
					var a = $(this).attr('name');
					$(this).attr('name',a+i);
				});
				prot.attr("class", "")
				////prot.find(".id").html(id);
				
				master.find("tbody").append(prot);
			});
			
			// Remove button functionality
			$("table.dynatable button.remove").live("click", function() {
				$(this).parents("tr").remove();
				//length =  $("table.dynatable tr").not('tr:first').length-1;
			});
			return false;
		});
	</script>
	<style>
		.dynatable {
			border: solid 1px #ffffff; 
			border-collapse: collapse;
		}
		.dynatable th,
		.dynatable td {
			border: solid 1px #ffffff; 
			padding: 2px 10px;
			width: 50px;
			text-align: center;
		}
		.dynatable .prototype {
			display:none;
		}
	</style>
	<table id="dynTable" class="dynatable">
		<thead>
			<tr>
				<th id='tblHeaderCp' colspan=8></th>
			</tr>
		</thead>
		<thead>
			<tr>
				<th>Attn:</th>
				<th>Name</th>
				<th>Designation</th>
				<th>National Id</th>
				<th>Mobile</th>
				<th>Landline</th>
				<th>Email Id</th>				
			</tr>
		</thead>
		<tbody id='cprowbody'>
		</tbody>
	</table>
</div>

</div> <!--Main Div-->
</form>
&nbsp;&nbsp;<font color=red><label id="cc"></label></font>
</body>
</html>
<!--variations					
					
1	tenant name				
2	tenure				
3	rent				
4	sc				
					
	select tenant				
					
	new name				
					
	rentcycle				
					
	rent type				
					
	fromdate	todate	yearly hike	amount	
					
	sctype				
					
	fromdate	todate	yearly hike	amount	-->
