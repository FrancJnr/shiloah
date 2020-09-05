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
		$("#tblheader").text("Create Department");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectmoduleheader").hide();
		$("#name").focus();
		$("#editTr").hide()
		$("#newTr").show();
		$("#name").val("");		
	});
	$('#btnEdit').click(function(){
		$("#tblheader").css('background-color', '#4ac0d5');
		$("#tblheader").text("Edit Department");
		$("#exampleDiv").hide();
		$("#dataManipDiv").show();
		$("#selectmoduleheader").show();
		$("#departmentmasid").focus();
		$("#editTr").show()
		$("#newTr").hide();
		$("#name").val("");		
		loadDept();
	});
	function loadDept()
	{
		var url="load_dept.php?item=dept";					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$('#departmentmasid').empty();
						$('#departmentmasid').append( new Option("-----Select Department-----","",true,false) );		
						$.each(data.myResult, function(i,response){
							$('#departmentmasid').append( new Option(response.name,response.departmentmasid,true,false) );
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
		if(jQuery.trim($("#name").val()) == "")
		{
			alert("Please Enter Name");
			$("#name").focus();
			return false;
		}
		var url="save_dept.php?action=Save";		
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
		if($("#departmentmasid option:selected").val()== "")
		{
			alert("Please select Department");return false;
		}
		if(jQuery.trim($("#name").val()) == "")
		{
			alert("Please Enter Name"); return false;
		}
		var url="save_dept.php?action=Update";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$("#name").val("");
						loadDept();
						alert(response.msg);
					}
					else
					{
						alert(response.s);
					}
				});
		});
	});
	
	$("#departmentmasid").change(function(){
		var departmentmasid = $('#departmentmasid').val();
		//alert(departmentmasid);
		$('#name').focus();
		if($departmentmasid !="")
		{
			var url="load_dept.php?item=dept&itemval="+departmentmasid;					
			$.getJSON(url,function(data){
				$.each(data.error, function(i,response){
					if(response.s == "Success")
					{
						$.each(data.myResult, function(i,response){
							$("#name").val(response.name);							
						});
					}
					else
					{
						alert(response.s);
						$("#name").val("");						
						//$("#active").removeAttr('checked')
					}
				});             
                        });
		}
		else
		{
			alert("Please select Department");
			$("#name").val("");			
			//$("#active").removeAttr('checked')
		}
	});
});
</script>		
</head>

<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>Department Master</h1>
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
							<th>Department</th>							
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					$sql = "select * from mas_department";
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
									$tr =  "<tr>
									<td class='center'>".$i++."</td>
									<td>".$name."</td>																		
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
							<th>Department</th>														
						</tr>
					</tfoot>
				</table>
</div>
<div id="dataManipDiv">
<table id="usertbl" class="table1" width="60%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Create Department	
			</th>
		</tr>
	</thead>
	<tbody>
	<tr id="selectmoduleheader">
		<td>
			Select Department <font color="red">*</font>
		</td>
		<td>
			<select id="departmentmasid" name="departmentmasid">
				<option value="" selected>--Select Department--</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Department <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="name" name="name">
		</td>
	</tr>		
	<tr id="newTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnSave">Create Department</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update Department</button>
		</td>
	</tr>
	</tbody>
</table>
</div>

</div> <!--Main Div-->
</form>
</body>
</html>
