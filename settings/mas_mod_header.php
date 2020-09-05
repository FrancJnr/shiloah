<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Module Header Master</title>
        <link rel="stylesheet" type="text/css" href="../styles.css">
<?php
		session_start();
		if (! isset($_SESSION['myusername']) ){
			header("location:../index.php");
		}
		include('../config.php');
		include('../MasterRef_Folder.php');
?>
<script type="text/javascript" langumoduleheader="javascript">
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
		$("#tblheader").text("Create Module Header");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectmoduleheader").hide();
		$("#moduleheader").focus();
		$("#editTr").hide()
		$("#newTr").show();
		$("#moduleheader").val("");		
	});
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Module Header");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectmoduleheader").show();
		$("#moduleheadermasid").focus();
		$("#editTr").show()
		$("#newTr").hide();
		$("#moduleheader").val("");		
		loadModuleheader();
	});
	function loadModuleheader()
	{
		var url="load_mod_header.php?item=loadModuleheader";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#moduleheadermasid').empty();
						$('#moduleheadermasid').append( new Option("-----Select module header-----","",true,false) );		
						$.each(data.myResult, function(i,response){
							$('#moduleheadermasid').append( new Option(response.moduleheader,response.moduleheadermasid,true,false) );
						});
					}
					else
					{
						alert(response.s);
					}
				});		
			});
	}
	$('#btnView').click(function(){
		$('form').submit();
	});
	
	$('#btnSave').click(function(){		
		if(jQuery.trim($("#moduleheader").val()) == "")
		{
			alert("Please Enter Module Header");
			$("#moduleheader").focus();
			return false;
		}
		var url="save_mod_header.php?action=Save";		
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
		if($("#moduleheadermasid option:selected").val()== "")
		{
			alert("Please select Module Header");return false;
		}
		if(jQuery.trim($("#moduleheader").val()) == "")
		{
			alert("Please Enter Module Header"); return false;
		}
		var url="save_mod_header.php?action=Update";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$("#moduleheader").val("");
						loadModuleheader();
						alert(response.msg);
					}
					else
					{
						alert(response.s);
					}
				});
		});
	});
	
	$("#moduleheadermasid").change(function(){
		var $moduleheadermasid = $('#moduleheadermasid').val();
		$('#moduleheader').focus();
		if($moduleheadermasid !="")
		{
			var url="load_mod_header.php?item=moduleheaderDetails&itemval="+$moduleheadermasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$.each(data.myResult, function(i,response){
							$("#moduleheader").val(response.moduleheader);							
						});
					}
					else
					{
						alert(response.s);
						$("#moduleheader").val("");						
						$("#active").removeAttr('checked')
					}
				});             
                        });
		}
		else
		{
			alert("Please select Currency");
			$("#moduleheader").val("");			
			$("#active").removeAttr('checked')
		}
	});
});
</script>		
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>Module Header Master</h1>
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
							<th>Module Header</th>							
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					$sql = "select * from mas_module_header";
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {
									//echo $row['table_name'];
									$moduleheader = $row["moduleheader"];
									$tr =  "<tr>
									<td class='center'>".$i++."</td>
									<td>".$moduleheader."</td>																		
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
							<th>Module Header</th>														
						</tr>
					</tfoot>
				</table>
</div>
<div id="dataManipDiv">
<table id="usertbl" class="table1" width="60%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Create Module Header	
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selectmoduleheader">
		<td>
			Select Module Header <font color="red">*</font>
		</td>
		<td>
			<select id="moduleheadermasid" name="moduleheadermasid">
				<option value="" selected>--Select Module Header--</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Module Header <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="moduleheader" name="moduleheader">
		</td>
	</tr>		
	<tr id="newTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnSave">Create Module Header</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update Module Header</button>
		</td>
	</tr>
	</tbody>
</table>
</div>

</div> <!--Main Div-->
</form>
</body>
</html>
