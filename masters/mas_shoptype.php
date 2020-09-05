<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Shoptype Master</title>
        <link rel="stylesheet" type="text/css" href="../styles.css">
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
		$("#tblheader").text("Create New Shoptype");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selecShoptype").hide();
		$("#shoptype").focus();
		$("#editTr").hide()
		$("#newTr").show();
		$("#shoptype").val("");
		$("#description").val("");
		$("#active").attr('checked','checked');
	});
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Existing Shoptype");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selecShoptype").show();
		$("#shoptypemasid").focus();
		$("#editTr").show()
		$("#newTr").hide();
		$("#shoptype").val("");
		$("#description").val("");
		$("#active").removeAttr('checked')
		
		var url="load_shoptype.php?item=loadShoptype";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#shoptypemasid').empty();
						$('#shoptypemasid').append( new Option("-----Select shoptype-----","",true,false) );		
						$.each(data.myResult, function(i,response){
							$('#shoptypemasid').append( new Option(response.shoptype,response.shoptypemasid,true,false) );
						});
					}
					else
					{
						alert(response.s);
					}
				});		
			});
	});
	$('#btnView').click(function(){
		$('form').submit();
	});
	
	$('#btnSave').click(function(){
		if(jQuery.trim($("#shoptype").val()) == "")
		{
			alert("Please Enter Shoptype");
			$("#shoptype").focus();
			return false;
		}
		var url="save_shoptype.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$('input[type=text]').val('');						
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
		if($("#shoptypemasid option:selected").val()== "")
		{
			alert("Please select Shoptype");return false;
		}
		if(jQuery.trim($("#shoptype").val()) == "")
		{
			alert("Please Enter Shoptype"); return false;
		}
		var url="save_shoptype.php?action=Update";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$("#shoptype").val("");
						$("#description").val("");
						$("#active").removeAttr('checked')
						alert(response.msg);
					}
					else
					{
						alert(response.s);
					}
				});
		});
	});
	
	$("#shoptypemasid").change(function(){
		var $shoptypemasid = $('#shoptypemasid').val();
		$('#shoptype').focus();
		if($shoptypemasid !="")
		{
			var url="load_shoptype.php?item=shoptypeDetails&itemval="+$shoptypemasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$.each(data.myResult, function(i,response){
							$("#shoptype").val(response.shoptype);
							$("#description").val(response.description);
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
						$("#shoptype").val("");
						$("#description").val("");
						$("#active").removeAttr('checked')
					}
				});             
                        });
		}
		else
		{
			alert("Please select Currency");
			$("#shoptype").val("");
			$("#description").val("");
			$("#active").removeAttr('checked')
		}
	});
});
</script>		
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>Shoptype Master</h1>
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
							<th>Shoptype</th>
							<th>Description</th>
							<th>Created</th>
							<th>Date</th>
							<th>Modified</th>
							<th>Date</th>
							<th>Active</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					 $sql = "select * from mas_shoptype";
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {
									//echo $row['table_name'];
									$shoptype = $row["shoptype"];
									$description = $row["description"];
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
									<td>".$shoptype."</td>
									<td>".$description."</td>
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
							<th>Shoptype</th>
							<th>Description</th>
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
				Create New Shoptype	
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selecShoptype">
		<td>
			Select Shoptype <font color="red">*</font>
		</td>
		<td>
			<select id="shoptypemasid" name="shoptypemasid">
				<option value="" selected>--Select Shoptype--</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Shoptype <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="shoptype" name="shoptype">
		</td>
	</tr>
	<tr>
		<td>
			Description
		</td>
		<td>
			<input type="text" id="description" name="description">
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
			<button type="button" id="btnSave">Create New Shoptype</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update Shoptype</button>
		</td>
	</tr>
	</tbody>
</table>
</div>

</div> <!--Main Div-->
</form>
</body>
</html>
