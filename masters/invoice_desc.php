<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Invoice Description</title>
<?php
		session_start();
		if (! isset($_SESSION['myusername']) ){
			header("location:../index.php");
		}
		include('../config.php');
		include('../MasterRef_Folder.php');
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$('#dataManipDiv').hide();
	
	oTable = $('#example').dataTable({
		"bJQueryUI": true,			
		"sPaginationType": "full_numbers"
	});
	$('#btnNew').click(function(){
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("Create New Description");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectinvoicedesc").hide();
		$("#invoicedesc").focus();
		$("#editTr").hide()
		$("#newTr").show();
		$("#invoicedesc").val("");
		$("#active").attr('checked','checked');
	});
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Invoice Description");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectinvoicedesc").show();		
		$("#editTr").show()
		$("#newTr").hide();
		$("#invoicedesc").val("");
		$("#vat").val("");
		$("#active").removeAttr('checked')
                load_desc();
	});
        function load_desc()
        {
            var url="load_invoice_desc.php?item=invoicedescription";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#invoicedescmasid').empty();
						$('#invoicedescmasid').append( new Option("-----Select Invoice Desc-----","",true,false) );		
						$.each(data.myResult, function(i,response){
							$('#invoicedescmasid').append( new Option(response.invoicedesc,response.invoicedescmasid,true,false) );
						});
					}
					else
					{
						alert(response.s);
					}
				});		
			});
                        $("#invoicedescmasid").focus();
        }
	$('#btnView').click(function(){
		$('form').submit();
	});
	
	$('#btnSave').click(function(){
		if(jQuery.trim($("#invoicedesc").val()) == "")
		{
			alert("Please Enter Description");
			$("#invoicedesc").focus();
			return false;
		}
		var url="save_invoice_desc.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{					    
                                            $("#invoicedesc").val("");
					}
					else
					{
						alert(response.s);
					}
					alert(response.msg);
				});
		});
	});
	
	$('#btnUpdate').click(function(){
		if($("#invoicedescmasid option:selected").val()== "")
		{
			alert("Please select Description");return false;
		}
		if(jQuery.trim($("#invoicedesc").val()) == "")
		{
			alert("Please Enter Description"); return false;
		}
		var url="save_invoice_desc.php?action=Update";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$("#invoicedesc").val("");
						$("#vat").val("");
						$("#active").removeAttr('checked')
                                                load_desc();
						alert(response.msg);                                                
					}
					else
					{
						alert(response.s);
					}
				});
		});
	});
	
	$("#invoicedescmasid").change(function(){
		var $invoicedescmasid = $('#invoicedescmasid').val();
		$('#invoicedesc').focus();
		if($invoicedescmasid !="")
		{
			var url="load_invoice_desc.php?item=invoicedescdetails&itemval="+$invoicedescmasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$.each(data.myResult, function(i,response){
							$("#invoicedesc").val(response.invoicedesc);
							$("#vat").val(response.vat);
							$act = response.active;
							if($act == "1")
							{
								$("#active").attr('checked','checked');
							}
							else
							{
								$("#active").removeAttr('checked')
							}
						});
					}
					else
					{
						alert(response.s);
						$("#invoicedesc").val("");
						$("#vat").val("");
						$("#active").removeAttr('checked')
					}
				});             
                        });
		}
		else
		{			
			$("#invoicedesc").val("");
			$("#vat").val("");
			$("#active").removeAttr('checked')
		}
	});
        $(".numbersOnly").keydown(function(event) {	
        // Allow: backspace, delete, tab, escape, and enter
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }	
    });
});
</script>		
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>Invoice Description Master</h1>
<div id="menuDiv" width="100%" align="right">
<table>
		<tr>
			<td> <button class="buttonNew" type="button" id="btnNew"> New </button> </td>
			<td> <button class="buttonEdit" type="button" id="btnEdit"> Edit </button> </td>
			<td> <button class="buttonView" type="button" id="btnView"> View </button> </td>
		</tr>
	</table>
</div>
<div id='demo'></div>
<br>
<div id="exampleDiv" width="100%">
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
					<thead>
						<tr>
							<th>Index</th>							
							<th>Description</th>
							<th>Vat</th>							
							<th>Status</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					$sql = "select * from invoice_desc";
					$result=mysql_query($sql);
					if($result != null) 
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {									
									$invoicedesc = $row["invoicedesc"];									
									$active = $row["active"];
                                                                        $vat = $row["vat"];
									if($active == 1)
									{
										$active = "active";
									}
									else
									{
										$active = "disabled";
									}
									$tr =  "<tr>
									<td class='center'>".$i++."</td>
									<td>".$invoicedesc."</td>
                                                                        <td>".$vat." %</td>
									<td>".$active."</td>
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
							<th>Description</th>
							<th>Vat</th>							
							<th>Status</th>
						</tr>
					</tfoot>
				</table>
</div>
<div id="dataManipDiv">
<table id="usertbl" class="table1" width="60%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Create New InvoiceDescription	
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selectinvoicedesc">
		<td>
			Select Invoice Description <font color="red">*</font>
		</td>
		<td>
			<select id="invoicedescmasid" name="invoicedescmasid">
				<option value="" selected>--Select Invoice Description--</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Description <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="invoicedesc" name="invoicedesc">
		</td>
	</tr>
	<tr>
		<td>
			Vat
		</td>
		<td>
			<input type="text" id="vat" name="vat" class="numbersOnly" value='16' maxlength='2' />%
		</td>
	</tr>
	<tr>
		<td>
			Active
		</td>
		<td>
			<input type="checkbox" id="active" name="active" checked>
		</td>
	</tr>
	<tr id="newTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnSave">Save</button>
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
</div>

</div> <!--Main Div-->
</form>
</body>
</html>
