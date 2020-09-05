<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Expense Head</title>
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
		$("#tblheader").text("Create Expense Group");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectexpgroup").hide();
		$("#expgroup").focus();
		$("#editTr").hide()
		$("#newTr").show();
		$("#expgroup").val("");
		$("#active").attr('checked','checked');
	});
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Expense Group");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectexpgroup").show();		
		$("#editTr").show()
		$("#newTr").hide();
		$("#expgroup").val("");
		$("#vat").val("");
		$("#active").removeAttr('checked')
                load_desc();
	});
        function load_desc()
        {
            var url="load_exp_group.php?item=expgroup";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#expgroupmasid').empty();
						$('#expgroupmasid').append( new Option("-----Select Group-----","",true,false) );		
						$.each(data.myResult, function(i,response){
							$('#expgroupmasid').append( new Option(response.expgroup,response.expgroupmasid,true,false) );
						});
					}
					else
					{
						alert(response.s);
					}
				});		
			});
                        $("#expgroupmasid").focus();
        }
	$('#btnView').click(function(){
		$('form').submit();
	});
	
	$('#btnSave').click(function(){
		if(jQuery.trim($("#expgroup").val()) == "")
		{
			alert("Please Enter Group Name");
			$("#expgroup").focus();
			return false;
		}
		var url="save_exp_group.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{					    
                                            $("#expgroup").val("");
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
		if($("#expgroupmasid option:selected").val()== "")
		{
			alert("Please select Narration");return false;
		}
		if(jQuery.trim($("#expgroup").val()) == "")
		{
			alert("Please Enter Narration"); return false;
		}
		var url="save_exp_group.php?action=Update";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$("#expgroup").val("");						
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
	
	$("#expgroupmasid").change(function(){
		var $expgroupmasid = $('#expgroupmasid').val();
		$('#expgroup').focus();
		if($expgroupmasid !="")
		{
			var url="load_exp_group.php?item=expgroupdetails&itemval="+$expgroupmasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$.each(data.myResult, function(i,response){
							$("#expgroup").val(response.expgroup);
							$("#exptype").val(response.exptype);
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
						$("#expgroup").val("");
                                                $("#exptype").val("");						
						$("#active").removeAttr('checked')
					}
				});             
                        });
		}
		else
		{			
			$("#expgroup").val("");
			$("#exptype").val("");						
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
<h1>Expense Group Entry</h1>
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
                                                        <th>Type</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					$sql = "select * from mas_exp_group";
					$result=mysql_query($sql);
					if($result != null) 
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {									
									$expgroup = $row["expgroup"];
                                                                        $exptype = $row["exptype"];
                                                                        if($exptype == 0)
									{
										$exptype = "In-Direct";
									}
									else
									{
										$exptype = "Direct";
									}
                                                                        
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
                                                                        <td>".$exptype."</td>
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
                                                        <th>Type</th>
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
				Create New Exp Group	
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selectexpgroup">
		<td>
			Select  Group <font color="red">*</font>
		</td>
		<td>
			<select id="expgroupmasid" name="expgroupmasid">
				<option value="" selected>--Select Group--</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Expense Group <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="expgroup" name="expgroup">
		</td>
	</tr>
        <tr>
		<td>
			Expense Type
		</td>
		<td>
			<select id="exptype" name="exptype">
				<option value="" selected>--Select Type--</option>
				<option value='0'>In-Direct</option>
				<option value='1'>Direct</option>
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
