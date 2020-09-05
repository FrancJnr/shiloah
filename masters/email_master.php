<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Email</title>
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
		$("#tblheader").text("Create New Email");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectinvoicedesc").hide();
		$("#invoicedesc_email").focus();
		$("#editTr").hide()
		$("#newTr").show();
		$("#invoicedesc_email").val("");
		$("#active").attr('checked','checked');
	});
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Email");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectinvoicedesc").show();		
		$("#editTr").show()
		$("#newTr").hide();
		$("#invoicedesc_email").val("");
		$("#staff").val("");
		$("#active").removeAttr('checked')
                load_desc();
	});
        function load_desc()
        {
            var url="load_invoice_desc.php?item=emaildetails";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#emailmasid').empty();
						$('#emailmasid').append( new Option("-----Select Email-----","",true,false) );		
						$.each(data.myResult, function(i,response){
							$('#emailmasid').append( new Option(response.email,response.id,true,false) );
						});
					}
					else
					{
						alert(response.s);
					}
				});		
			});
                        $("#emailmasid").focus();
        }
	$('#btnView').click(function(){
		$('form').submit();
	});
	
	$('#btnSaveEmail').click(function(){
		if(jQuery.trim($("#invoicedesc_email").val()) == "")
		{
			alert("Please Enter Email");
			$("#invoicedesc_email").focus();
			return false;
		}
		var url="save_invoice_desc.php?action=emailsave";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{					    
                                            $("#invoicedesc_email").val("");
											 $("#staff").val("");
					}
					else
					{
						alert(response.s);
					}
					alert(response.msg);
				});
		});
	});
	
	$('#btnEUpdate').click(function(){
		if($("#emailmasid option:selected").val()== "")
		{
			alert("Please select Email");return false;
		}
		if(jQuery.trim($("#invoicedesc_email").val()) == "")
		{
			alert("Please Enter Email"); return false;
		}
		var url="save_invoice_desc.php?action=EmailUpdate";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$("#invoicedesc_email").val("");
						$("#staff").val("");
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
	
	$("#emailmasid").change(function(){
		var $emailmasid = $('#emailmasid').val();
		$('#invoicedesc_email').focus();
		if($emailmasid !="")
		{
			var url="load_invoice_desc.php?item=emaildescdetails&itemval="+$emailmasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$.each(data.myResult, function(i,response){
							$("#invoicedesc_email").val(response.email);
							$("#staff").val(response.staff_name);
							 $("#departmnt").val(response.departmentmasid);
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
						$("#invoicedesc_email").val("");
						$("#staff").val("");
						$("#active").removeAttr('checked')
					}
				});             
                        });
		}
		else
		{			
			$("#invoicedesc_email").val("");
			$("#staff").val("");
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
<h1>Email Master</h1>
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
							<th>Email</th>
							<th>Display Name</th>	
							<th>Department</th>								
							<th>Status</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					$sql = "select * from mas_email LEFT join mas_department ON mas_department.departmentmasid = mas_email.departmentmasid ";
					$result=mysql_query($sql);
					if($result != null) 
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {									
									$email = $row["email"];									
									$active = $row["active"];
                                                                        $staff_name = $row["staff_name"];
																		$dep_name = $row["name"];
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
									<td>".$email."</td>
                                                                        <td>".$staff_name." </td>
																		 <td>".$dep_name." </td>
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
							<th>Email</th>
							<th>Display Name</th>
							<th>Department</th>								
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
				Create New Email	
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selectinvoicedesc">
		<td>
			Select Email <font color="red">*</font>
		</td>
		<td>
			<select id="emailmasid" name="emailmasid">
				<option value="" selected>--Select Email--</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Email <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="invoicedesc_email" name="invoicedesc_email">
		</td>
	</tr>
	<tr>
		<td>
			Display Name
		</td>
		<td>
			<input type="text" id="staff" name="staff"   />
		</td>
	</tr>
		<tr>
		<td>
			Departments
		</td>
		<td>
		<select id="departmnt" name="departmnt">
		<?php
		$sql = "select * from mas_department";
					$result=mysql_query($sql);
					while ($row = mysql_fetch_array($result)){
echo "<option value=".$row['departmentmasid'].">" . $row['name'] . "</option>";
}
					?>
			</select>
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
			<button type="button" id="btnSaveEmail">Save</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnEUpdate">Update</button>
		</td>
	</tr>
	</tbody>
</table>
</div>

</div> <!--Main Div-->
</form>
</body>
</html>
