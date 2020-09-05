<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Company Master</title>
        <link rel="stylesheet" type="text/css" href="../styles.css">
<?php
		session_start();
		if (! isset($_SESSION['myusername']) ){
			header("location:../index.php");
		}
		include('../config.php');
		include('../MasterRef_Folder.php');
?>
<script type="text/javascript" langucompany="javascript">
$(document).ready(function() {
    (function($) {
   $.fn.fixMe = function() {
      return this.each(function() {
         var $this = $(this),
            $t_fixed;
         function init() {
            $this.wrap('<div class="dataManipDiv" />');
          //  $this.wrap('<div class="exampleDiv" />');
          //  $this.wrap('<div class="menuDiv" />');
            $t_fixed = $this.clone();
            $t_fixed.find("tbody").remove().end().addClass("fixed").insertBefore($this);
            resizeFixed();
         }
         function resizeFixed() {
            $t_fixed.find("th").each(function(index) {
               $(this).css("width",$this.find("th").eq(index).outerWidth()+"px");
            });
         }
         function scrollFixed() {
            var offset = $(this).scrollTop(),
            tableOffsetTop = $this.offset().top,
            tableOffsetBottom = tableOffsetTop + $this.height() - $this.find("thead").height();
            if(offset < tableOffsetTop || offset > tableOffsetBottom)
               $t_fixed.hide();
            else if(offset >= tableOffsetTop && offset <= tableOffsetBottom && $t_fixed.is(":hidden"))
               $t_fixed.show();
         }
         $(window).resize(resizeFixed);
         $(window).scroll(scrollFixed);
         init();
      });
   };
})(jQuery);

//$(document).ready(function(){
   $("table").fixMe();
   $(".up").click(function() {
      $('html, body').animate({
      scrollTop: 0
   }, 2000);
 });
	$(function() {
		$("#acyearfrom").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"dd-mm-yy"
		});
		$("#acyearto").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"dd-mm-yy"
		});
	});
	$('#dataManipDiv').hide();
	$('#r5').hide();
	$('#r6').hide();
	$('#r7').hide();
	$('#r8').hide();
	$('#r9').hide();
	$('#r10').hide();
	$('#r11').hide();
	$('#r12').hide();
	$('#r13').hide();
	$('#r14').hide();
	$('#r15').hide();
	$('#r16').hide();
	$('#r17').hide();
	
	$('#show1').click(function(){
		//hide show rows
		$('#r5').toggle();
		$('#r6').toggle();
		$('#r7').toggle();
		$('#r8').toggle();
		$('#r9').toggle();
		$('#r10').toggle();
		$('#r11').toggle();
		$('#r12').toggle();
		$('#r13').toggle();
		$('#r14').toggle();
		$('#r15').toggle();
		$('#r16').toggle();
		$('#r17').toggle();
		return false;
	});
	oTable = $('#example').dataTable({
		"bJQueryUI": true,			
		"sPaginationType": "full_numbers"			
	});
	$('#btnNew').click(function(){
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("Create New Company");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selecCompany").hide();
		$("#companyname").focus();
		$("#editTr").hide()
		$("#newTr").show();
		$("#active").attr('checked','checked');
		$('input[type=text]').val('');
		
	});
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Existing Company");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selecCompany").show();
		$("#companymasid").focus();
		$("#editTr").show()
		$("#newTr").hide();
		$('input[type=text]').val('');	
		$("#active").removeAttr('checked')
		
		var url="load_company.php?item=load";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#companymasid').empty();
						$('#companymasid').append( new Option("-----Select company-----","",true,false) );		
						$.each(data.myResult, function(i,response){
							$('#companymasid').append( new Option(response.companyname,response.companymasid,true,false) );
						});
					}
					else
					{
						jAlert(response.s);
					}
				});		
			});
	});
	$('#btnView').click(function(){
		$('form').submit();
	});
	
	$('#btnSave').click(function(){
		if(jQuery.trim($("#companyname").val()) == "")
		{
			jAlert("Please enter company name");return false;
		}
		if(jQuery.trim($("#pin").val()) == "")
		{
			jAlert("Please enter pin");return false;
		}
		if(jQuery.trim($("#regno").val()) == "")
		{
			jAlert("Please enter reg no");return false;
		}
		if(jQuery.trim($("#acyearfrom").val()) == "")
		{
			jAlert("Please select a/c year from");return false;
		}
		if(jQuery.trim($("#acyearto").val()) == "")
		{
			jAlert("Please select a/c year to");return false;
		}
		var url="save_company.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$('input[type=text]').val('');						
					}
					else
					{
						jAlert(response.s);
						//$("#cc").html(response.msg);
						
					}
					jAlert(response.msg);
					//$("#cc").html(response.msg);
				});
		});
	});
	
	$('#btnUpdate').click(function(){
		if($("#companymasid option:selected").val()== "")
		{
			jAlert("Please select Company");return false;
		}
		if(jQuery.trim($("#companyname").val()) == "")
		{
			jAlert("Please Enter Company");return false;
		}
		if(jQuery.trim($("#pin").val()) == "")
		{
			jAlert("Please enter pin");return false;
		}
		if(jQuery.trim($("#regno").val()) == "")
		{
			jAlert("Please enter reg no");return false;
		}
		if(jQuery.trim($("#acyearfrom").val()) == "")
		{
			jAlert("Please select a/c year from");return false;
		}
		if(jQuery.trim($("#acyearto").val()) == "")
		{
			jAlert("Please select a/c year to");return false;
		}
		var url="save_company.php?action=Update";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$('input[type=text]').val('');	
						$("#active").removeAttr('checked')
						jAlert(response.msg);
					}
					else
					{
						jAlert(response.s);
						//$("#cc").html(response.msg);
					}
					jAlert(response.msg);
					//$("#cc").html(response.msg);
				});
		});
	});
	
	$("#companymasid").change(function(){
		var $companymasid = $('#companymasid').val();
		$('#company').focus();
		if($companymasid !="")
		{
			var url="load_company.php?item=details&itemval="+$companymasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$.each(data.myResult, function(i,response){
							$("#companyname").val(response.companyname);
							$("#pin").val(response.pin);
							$("#regno").val(response.regno);
							$("#acyearfrom").val(response.d1);
							$("#acyearto").val(response.d2);
							$("#address1").val(response.address1);
							$("#address2").val(response.address2);
							$("#poboxno").val(response.poboxno);
							$("#city").val(response.city);
							$("#state").val(response.state);
							$("#pincode").val(response.pincode);
							$("#country").val(response.country);
							$("#telephone1").val(response.telephone1);
							$("#telephone2").val(response.telephone2);
							$("#fax").val(response.fax);
							$("#emailid").val(response.emailid);
							$("#website").val(response.website);
							$("#remarks").val(response.remarks);
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
						jAlert(response.s);
						$('input[type=text]').val('');	
						$("#active").removeAttr('checked')
					}
				});             
                        });
		}
		else
		{
			jAlert("Please select Company");
			$('input[type=text]').val('');	
			$("#active").removeAttr('checked')
		}
	}); 
});
</script>		
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>Company Master</h1>
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
							<th>Company</th>
							<th>Code</th>
							<th>Created</th>
							<th>Date</th>
							<th>Modified</th>
							<th>Date</th>
							<th>Active</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					 $sql = "select * from mas_company";
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {
									//echo $row['table_name'];
									$companyname = $row["companyname"];
									$companycode = $row["companycode"];
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
									<td>".$companyname."</td>
									<td>".$companycode."</td>
									<td>".$cby."</td>
									<td>".$cdt."</td>
									<td>".$mby."</td>
									<td>".$mdt."</td>
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
							<th>Company</th>
							<th>Code</th>
							<th>Created</th>
							<th>Date</th>
							<th>Modified</th>
							<th>Date</th>
							<th>Active</th>
						</tr>
					</tfoot>
				</table>
</div>
<div id="dataManipDiv">
<table id="usertbl" class="table1" width="60%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Create New Company	
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selecCompany">
		<td>
			Select Company <font color="red">*</font>
		</td>
		<td>
			<select id="companymasid" name="companymasid">
				<option value="" selected>--Select Company--</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Company <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="companyname" name="companyname">
		</td>
	</tr>
	<tr>
		<td>
			PIN <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="pin" name="pin">
		</td>
	</tr>
	<tr>
		<td>
			REG NO <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="regno" name="regno">
		</td>
	</tr>
		<tr>
		<td colspan=2><b>Accounting Year Details</b></td>
	</tr>
	<tr id="r1">
		<td>
			A/c year from <font color="red">*</font>
		</td>
		<td>
			<p><input type="text" id="acyearfrom" name="acyearfrom"></p>
		</td>
	</tr>
	<tr id="r2">
		<td>
			A/c year to <font color="red">*</font>
		</td>
		<td>
			<p><input type="text" id="acyearto" name="acyearto"></p>			
		</td>
	</tr>
	</tr>
	<tr>
		<td><b>Address Details</b></td>
		<td><button id="show1"> >> </button></td>
	</tr>
	<tr id="r5">
		<td>
			Address 1
		</td>
		<td>
			<input type="text" id="address1" name="address1">
		</td>
	</tr>
	<tr id="r6">
		<td>
			Address 2
		</td>
		<td>
			<input type="text" id="address2" name="address2">
		</td>
	</tr>
	<tr id="r7">
		<td>
			P.O.Box No
		</td>
		<td>
			<input type="text" id="poboxno" name="poboxno">
		</td>
	</tr>
	<tr id="r8">
		<td>
			City
		</td>
		<td>
			<input type="text" id="city" name="city">
		</td>
	</tr>
	<tr id="r9">
		<td>
			State
		</td>
		<td>
			<input type="text" id="state" name="state">
		</td>
	</tr>
	<tr id="r10">
		<td>
			Pincode
		</td>
		<td>
			<input type="text" id="pincode" name="pincode">
		</td>
	</tr>
	<tr id="r11">
		<td>
			Country
		</td>
		<td>
			<input type="text" id="country" name="country">
		</td>
	</tr>
	<tr id="r12">
		<td>
			Telephone 1
		</td>
		<td>
			<input type="text" id="telephone1" name="telephone1">
		</td>
	</tr>
	<tr id="r13">
		<td>
			Telephone 2
		</td>
		<td>
			<input type="text" id="telephone2" name="telephone2">
		</td>
	</tr>
	<tr id="r14">
		<td>
			Fax
		</td>
		<td>
			<input type="text" id="fax" name="fax">
		</td>
	</tr>
	<tr id="r15">
		<td>
			Email Id
		</td>
		<td>
			<input type="text" id="emailid" name="emailid">
		</td>
	</tr>
	<tr id="r16">
		<td>
			Website
		</td>
		<td>
			<input type="text" id="website" name="website">
		</td>
	</tr>
	<tr id="r17">
		<td>
			Remarks
		</td>
		<td>
			<input type="text" id="remarks" name="remarks">
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
			<button type="button" id="btnSave">Create New Company</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update Company</button>
		</td>
	</tr>
	</tbody>
</table>
</div>

</div> <!--Main Div-->

<label id="cc"></label>
</form>
</body>
</html>
