<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Accounting Master</title>
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
 });(function($) {
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
	oTable = $('#example').dataTable({
		"bJQueryUI": true,			
		"sPaginationType": "full_numbers"			
	});
	$('#btnNew').click(function(){
		$("#tblheader").css('background-color', '#fc9');
		$("#tblheader").text("Create New A/c Year");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selecAcyear").hide();
		$("#editTr").hide()
		$("#newTr").show();
		$("#active").attr('checked','checked');
		$('input[type=text]').val('');
		
	});
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Existing A/c Year");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selecAcyear").show();
		$("#acyearmasid").focus();
		$("#editTr").show()
		$("#newTr").hide();
		$('input[type=text]').val('');	
		$("#active").removeAttr('checked')
		
		var url="load_acyear.php?item=load";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#acyearmasid').empty();
						$('#acyearmasid').append( new Option("-----Select AC Year-----","",true,false) );		
						$.each(data.myResult, function(i,response){
							$y1 = response.acyearfrom;
							$y2 = response.acyearto;
							$acyear = $y1+" "+$y2;
							$('#acyearmasid').append( new Option($acyear,response.acyearmasid,true,false) );
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
		if(jQuery.trim($("#acyearfrom").val()) == "")
		{
			alert("Please select a/c year from");return false;
		}
		if(jQuery.trim($("#acyearto").val()) == "")
		{
			alert("Please select a/c year to");return false;
		}
		var url="save_acyear.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$('input[type=text]').val('');
						parent.location.reload();
					}
					else
					{
						alert(response.s);
						$("#cc").html(response.msg);
						
					}
					alert(response.msg);
					$("#cc").html(response.msg);
				});
		});
	});
	
	$('#btnUpdate').click(function(){
		if($("#acyearmasid option:selected").val()== "")
		{
			alert("Please select A/c Year");return false;
		}
		if(jQuery.trim($("#acyearfrom").val()) == "")
		{
			alert("Please select A/c year from");return false;
		}
		if(jQuery.trim($("#acyearto").val()) == "")
		{
			alert("Please select A/c year to");return false;
		}
		var url="save_acyear.php?action=Update";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$('input[type=text]').val('');	
						$("#active").removeAttr('checked')
						alert(response.msg);
						parent.location.reload();

					}
					else
					{
						alert(response.s);
						//$("#cc").html(response.msg);
					}
					alert(response.msg);
					//$("#cc").html(response.msg);
				});
		});
	});
	
	$("#acyearmasid").change(function(){
		var $acyearmasid = $('#acyearmasid').val();
		if($acyearmasid !="")
		{
			var url="load_acyear.php?item=details&itemval="+$acyearmasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$.each(data.myResult, function(i,response){
							$("#acyearfrom").val(response.d1);
							$("#acyearto").val(response.d2);
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
						alert(response.s);
						$('input[type=text]').val('');	
						$("#active").removeAttr('checked')
					}
				});             
                        });
		}
		else
		{
			alert("Please select A/c year");
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
<h1>A/c Year Master</h1>
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
							<th>A/c Year</th>							
							<th>Active</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					$companymasid  = $_SESSION['mycompanymasid'];
					$companyname = $_SESSION['mycompany'];
					$sql = "select a.acyearfrom,a.acyearto,a.active,b.companyname from mas_acyear  a
						inner join mas_company b on b.companymasid = a.companymasid
						where a.active='1';";
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {
									$y1 = date_format(new DateTime($row['acyearfrom']), "F-Y");
									$y2 = date_format(new DateTime($row['acyearto']), "F-Y");
									$acyear = $y1 ." ". $y2;
									$accompanyname = $row['companyname'];
									//$cby = $row["createdby"];
									//$cdt = $row["createddatetime"];
									//if(strtotime($cdt) != 0)
									//{
									//	//$cdt = date_format(new DateTime($cdt), "D d-F-Y H:i:s");
									//	$cdt = date_format(new DateTime($cdt), "d-m-Y");
									//	//E.g. Fri 03-August-2012 13:51:37
									//}
									//else
									//{
									//	$cdt="";
									//}
									//$mby = $row["modifiedby"];
									//$mdt = $row["modifieddatetime"];
									//if(strtotime($mdt) != 0)
									//{
									//	//$mdt = date_format(new DateTime($mdt), "D d-F-Y H:i:s");
									//	$mdt = date_format(new DateTime($mdt), "d-m-Y");
									//}
									//else
									//{
									//	$mdt = "";
									//}
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
									<td>".$accompanyname."</td>
									<td>".$acyear."</td>
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
							<th>A/c Year</th>							
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
				Create New A/c Year	
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selecAcyear">
		<td>
			Select A/c Year <font color="red">*</font>
		</td>
		<td>
			<select id="acyearmasid" name="acyearmasid">
				<option value="" selected>--Select A/c Year--</option>
			</select>
		</td>
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
			<button type="button" id="btnSave">Create New A/c Year</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update A/c Year</button>
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
