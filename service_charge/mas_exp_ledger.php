<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Expense Ldger</title>
<?php
		session_start();
		if (! isset($_SESSION['myusername']) ){
			header("location:../index.php");
		}
		include('../config.php');
		include('../MasterRef_Folder.php');
		function loadexpgroup()
		{
		    $sql = "select expgroupmasid, expgroup from mas_exp_group where active ='1';"; 
		    $result = mysql_query($sql);
		    if($result != null)
		    {
			    while($row = mysql_fetch_assoc($result))
			    {
				    echo("<option value=".$row['expgroupmasid'].">".$row['expgroup']."</option>");		
			    }
		    }
		}
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
		$("#tblheader").text("Create Expense Ldger");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectexpledger").hide();
		$("#expledger").focus();
		$("#editTr").hide()
		$("#newTr").show();
		$("#expledger").val("");
		$("#active").attr('checked','checked');
	});
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Expense Ldger");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectexpledger").show();		
		$("#editTr").show()
		$("#newTr").hide();
		$("#expledger").val("");
		$("#vat").val("");
		$("#active").removeAttr('checked')
                load_desc();
	});
        function load_desc()
        {
            var url="load_exp_ledger.php?item=expledger";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#expledgermasid').empty();
						$('#expledgermasid').append( new Option("-----Select Expense Ldger-----","",true,false) );		
						$.each(data.myResult, function(i,response){
							$('#expledgermasid').append( new Option(response.expledger,response.expledgermasid,true,false) );
						});
					}
					else
					{
						alert(response.s);
					}
				});		
			});
                        $("#expledgermasid").focus();
        }
	$('#btnView').click(function(){
		$('form').submit();
	});
	
	$('#btnSave').click(function(){		
		if(jQuery.trim($("#expgroupmasid").val()) == "")
		{
			alert("Please Select Expense Group");
			$("#v").focus();
			return false;
		}
		if(jQuery.trim($("#expledger").val()) == "")
		{
			alert("Please Enter Description");
			$("#expledger").focus();
			return false;
		}
		var url="save_exp_ledger.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{					    
                                            //$("#expgroupmasid").val("");
					    $("#expledger").val("");					    
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
		if($("#expledgermasid option:selected").val()== "")
		{
			alert("Please select Expense Ldger");return false;
		}
		if(jQuery.trim($("#expledger").val()) == "")
		{
			alert("Please Enter Expense Ldger"); return false;
		}
		var url="save_exp_ledger.php?action=Update";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$("#expledger").val("");
						$("#expgroupmasid").val("");
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
	
	$("#expledgermasid").change(function(){
		var $expledgermasid = $('#expledgermasid').val();
		$('#expledger').focus();
		if($expledgermasid !="")
		{
			var url="load_exp_ledger.php?item=expledgerdetails&itemval="+$expledgermasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$.each(data.myResult, function(i,response){
							$("#expledger").val(response.expledger);
							$("#expgroupmasid").val(response.expgroupmasid);
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
						$("#expledger").val("");
						$("#expgroupmasid").val("");
						$("#active").removeAttr('checked')
					}
				});             
                        });
		}
		else
		{			
			$("#expledger").val("");
			$("#expgroupmasid").val("");
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
<h1>Expense Ldger Entry</h1>
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
							<th>Expense Group</th>
							<th>Ledger</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					$sql = "select a.*,b.expgroup from mas_exp_ledger a
					inner join mas_exp_group b on b.expgroupmasid = a.expgroupmasid";
					$result=mysql_query($sql);
					if($result != null) 
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {									
									$expledger = $row["expledger"];
									$expgroup = $row['expgroup'];
									$active = $row["active"];                                                                        
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
									<td>".$expgroup."</td>
									<td>".$expledger."</td>                                                                        
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
							<th>Expense Group</th>
							<th>Ledger</th>
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
				Create New Expense Ldger	
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selectexpledger">
		<td>
			Select  Expense Ldger <font color="red">*</font>
		</td>
		<td>
			<select id="expledgermasid" name="expledgermasid">
				<option value="" selected>--Select Expense Ldger--</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Expense Group <font color="red">*</font>
		</td>
		<td>
			<select id="expgroupmasid" name="expgroupmasid">
				<option value="" selected>--Select Expense Group--</option>
				<?php loadexpgroup();?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Expense Ldger <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="expledger" name="expledger">
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
