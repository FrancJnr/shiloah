<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Common Area Description</title>
<?php
        session_start();
        if (! isset($_SESSION['myusername']) ){
                header("location:../index.php");
        }
        include('../config.php');
        include('../MasterRef_Folder.php');
        function loadexpledger()
        {
            $sql = "select expledgermasid, expledger from mas_exp_ledger where active ='1';"; 
            $result = mysql_query($sql);
            if($result != null)
            {
                    while($row = mysql_fetch_assoc($result))
                    {
                            echo("<option value=".$row['expledgermasid'].">".$row['expledger']."</option>");		
                    }
            }
        }
	function loadBuilding()
	{
	    $sql = "select buildingmasid, buildingname from mas_building where buildingmasid !='6'"; // exclude katangi
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
	$('#dataManipDiv').hide();
	
	oTable = $('#example').dataTable({
		"bJQueryUI": true,			
		"sPaginationType": "full_numbers"
	});
	$('#btnNew').click(function(){
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("Create Common Area Description");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectexpcomarea").hide();
		$("#expcomarea").focus();
		$("#editTr").hide()
		$("#newTr").show();
		$("#expcomarea").val("");
		$("#expledgermasid").val("");
		$("#buildingmasid").val("");
		$("#active").attr('checked','checked');
	});
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Common Area Description");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectexpcomarea").show();		
		$("#editTr").show()
		$("#newTr").hide();
		$("#expcomarea").val("");
		$("#vat").val("");
		$("#active").removeAttr('checked')
		$("#expledgermasid").val("");
		$("#buildingmasid").val("");
                load_desc();
	});
        function load_desc()
        {
            var url="load_com_area.php?item=expcomarea";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#expcomareamasid').empty();
						$('#expcomareamasid').append( new Option("-----Select Common Area-----","",true,false) );		
						$.each(data.myResult, function(i,response){
							$('#expcomareamasid').append( new Option(response.expcomarea,response.expcomareamasid,true,false) );
						});
					}
					else
					{
						alert(response.s);
					}
				});		
			});
                        $("#expcomareamasid").focus();
        }
	$('#btnView').click(function(){
		$('form').submit();
	});
	
	$('#btnSave').click(function(){
		if(jQuery.trim($("#buildingmasid").val()) == "")
		{
			alert("Please Select Building");
			$("#buildingmasid").focus();
			return false;
		}
		if(jQuery.trim($("#expledgermasid").val()) == "")
		{
			alert("Please Select Expense Ledger");
			$("#expledgermasid").focus();
			return false;
		}
		if(jQuery.trim($("#expcomarea").val()) == "")
		{
			alert("Please Enter Description");
			$("#expcomarea").focus();
			return false;
		}
		var url="save_com_area.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{					                                                
					    $("#expledgermasid").val("");
					    $("#buildingmasid").val("");
					    $("#expcomarea").val("");					    
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
		if($("#expcomareamasid option:selected").val()== "")
		{
			alert("Please select Expense Ldger");return false;
		}
		if(jQuery.trim($("#expcomarea").val()) == "")
		{
			alert("Please Enter Expense Ldger"); return false;
		}
		var url="save_com_area.php?action=Update";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$("#expcomarea").val("");
						$("#expledgermasid").val("");
						$("#buildingmasid").val("");
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
	
	$("#expcomareamasid").change(function(){
		var $expcomareamasid = $('#expcomareamasid').val();
		$('#expcomarea').focus();
		if($expcomareamasid !="")
		{
			var url="load_com_area.php?item=expcomareadetails&itemval="+$expcomareamasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$.each(data.myResult, function(i,response){
							$("#buildingmasid").val(response.buildingmasid);
							$("#expledgermasid").val(response.expledgermasid);
							$("#expcomarea").val(response.expcomarea);
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
						$("#expcomarea").val("");
						$("#expledgermasid").val("");
						$("#active").removeAttr('checked')
					}
				});             
                        });
		}
		else
		{			
			$("#expcomarea").val("");
			$("#expledgermasid").val("");
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
<h1>Common Area Description Entry</h1>
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
							<th>Expense Ledger</th>
							<th>Common Area</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					$sql = "select a.*,b.expledger from mas_exp_com_area a
					inner join mas_exp_ledger b on b.expledgermasid = a.expledgermasid";
					$result=mysql_query($sql);
					if($result != null) 
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {									
									$expcomarea = $row["expcomarea"];
									$expledger = $row['expledger'];
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
									<td>".$expledger."</td>
									<td>".$expcomarea."</td>                                                                        
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
							<th>Expense Ledger</th>
							<th>Common Area</th>
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
				Create New Common Area Description	
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selectexpcomarea">
		<td>
			Select  Common Area <font color="red">*</font>
		</td>
		<td>
			<select id="expcomareamasid" name="expcomareamasid">
				<option value="" selected>--Common Area--</option>
			</select>
		</td>
	</tr>
	<tr>
            <td>Building <font color="red">*</font></td>
            <td>
                <select id="buildingmasid" name="buildingmasid" style='width: 225px;'>
                    <option value="" selected>----Select Building----</option>
                    <?php loadBuilding();?>
                </select>    
            </td>
        </tr>
	<tr>
		<td>
			Expense Ledger <font color="red">*</font>
		</td>
		<td>
			<select id="expledgermasid" name="expledgermasid">
				<option value="" selected>--Select Expense Ledger--</option>
				<?php loadexpledger();?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Common Area Description <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="expcomarea" name="expcomarea">
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
