<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Bank Master</title>
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

	$('#dataManipDiv').hide();
	
	
	
	oTable = $('#example').dataTable({
		"bJQueryUI": true,			
		"sPaginationType": "full_numbers"			
	});
	$('#btnNew').click(function(){
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("Create New Bank");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectBank").hide();
		$("#name").focus();
		$("#editTr").hide()
		$("#newTr").show();
		$("#active").attr('checked','checked');
		$('input[type=text]').val('');
		
	});
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Existing Bank");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectBank").show();
		$("#bankmasid").focus();
		$("#editTr").show()
		$("#newTr").hide();
		$('input[type=text]').val('');	
		$("#active").removeAttr('checked')
		
		var url="load_bank.php?item=load";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						//alert(response.s);
						$('#bankmasid').empty();
						$('#bankmasid').append( new Option("-----Select Bank-----","",true,false) );		
						$.each(data.myResult, function(i,response){
							//console.log(response);
							$('#bankmasid').append( new Option(response.name,response.bankmasid,true,false) );
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
		if(jQuery.trim($("#name").val()) == "")
		{
			jAlert("Please enter bank name");return false;
		}
		
	
		if(jQuery.trim($("#alias").val()) == "")
		{
			jAlert("Please enter bank alias");return false;
		}
		var url="save_bank.php?action=Save";
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
		if($("#bankmasid option:selected").val()== "")
		{
			jAlert("Please select bank");return false;
		}
		if(jQuery.trim($("#name").val()) == "")
		{
			jAlert("Please Enter Bank");return false;
		}
		if(jQuery.trim($("#alias").val()) == "")
		{
			jAlert("Please enter bank alias");return false;
		}
		
		var url="save_bank.php?action=Update";
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
	
 	$("#bankmasid").change(function(){
		var bankmasid = $('#bankmasid').val();
		$('#bank').focus();
		if(bankmasid !="")
		{
			var url="load_bank.php?item=details&itemval="+bankmasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$.each(data.myResult, function(i,response){
							$("#name").val(response.name);
							$("#alias").val(response.alias);
				
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
			jAlert("Please select Bank");
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
<h1>Bank Master</h1>
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
							<th>Bank</th>
							<th>Bank Alias</th>
							<th>Created</th>
							<th>Date</th>
							<th>Modified</th>
							<th>Date</th>
							<th>Active</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					 $sql = "select * from mas_bank";
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {
									//echo $row['table_name'];
									$name = $row["name"];
									$alias = $row["alias"];
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
									<td>".$name."</td>
									<td>".$alias."</td>
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
							<th>Bank Name</th>
							<th>Bank Alias</th>
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
<table id="usertbl" class="table1" width="100%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Create New Bank	
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selectBank">
		<td>
			Select Bank <font color="red">*</font>
		</td>
		<td>
			<select id="bankmasid" name="bankmasid">
				<option value="" selected>--Select Bank--</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Bank <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="name" name="name">
		</td>
	</tr>
	<tr>
		<td>
			Alias <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="alias" name="alias">
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
			<button type="button" id="btnSave">Create New Bank</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update Bank</button>
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
