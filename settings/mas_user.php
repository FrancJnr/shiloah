<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>user</title>
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
	loadDept();
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
	//loadView();
	$("#userDiv").hide();
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
				$("#btnNew").click(function(){
					
					$("#tblheader").css('background-color', '#fc9');
					$("#tblheader").text("Create New User");
					$("#exampleDiv").hide();
					$("#userDiv").show();
					$("#selectEmpTr").show();
					$("#selectDepTr").show();
					$("#empmasid").focus();
					$("#selectUserTr").hide();
					$("#displayEmpTr").hide();
					$("#active").attr('checked','checked');
					$("#newTr").show();
					$("#editTr").hide();
					$("#username").val("");
					$("#password").val("");
					
					var url="load_user.php?item=User";					
					$.getJSON(url,function(data){
						$.each(data.error, function(i,response){
							if(response.msg == "Success")
							{
								$('#empmasid').empty();
								$('#empmasid').append( new Option("-----Select Employee-----","",true,false) );
								$.each(data.myResult, function(i,response){
									$('#empmasid').append( new Option(response.empname,response.empmasid,true,false) );
								});
							}
							else
							{
								alert(response.msg);
							}
						});		
					});



				});
				$("#btnEdit").click(function(){
					$("#tblheader").css('background-color', '#4ac0d5');
					$("#tblheader").text("Edit Existing User");
					
					$("#exampleDiv").hide();
					$("#userDiv").show();
			        $("#selectEmpTr").hide();
					$("#selectDepTr").show();
					$("#selectUserTr").show();
					$("#selectUser").focus();
					$("#displayEmpTr").show();
					$("#active").removeAttr('checked')
					$("#newTr").hide();
					$("#editTr").show();
					$("#username").val("");
					$("#password").val("");
					$("#lblEmpName").text("");
					loadUser();
					loadDept();
				});
				function loadUser()
				{
					var url="load_user.php?item=User";					
					$.getJSON(url,function(data){
						$.each(data.error, function(i,response){
							if(response.msg == "Success")
							{
								$('#usermasid').empty();
								$('#usermasid').append( new Option("-----Select User-----","",true,false) );
					
								$.each(data.myResult, function(i,response){
									$('#usermasid').append( new Option(response.username,response.usermasid,true,false) );
								});
							}
							else
							{
								alert(response.msg);
							}
						});		
					});
				}
			
				$('#empmasid').change(function (){
					$('#username').focus();
				});
			/* 	$('#departmentmasid').change(function (){
					alert('AH');
					//loadDept();
				}); */
					function loadDept()
				{
					var url="load_dept.php?item=dept";					
					$.getJSON(url,function(data){
						$.each(data.error, function(i,response){
							if(response.msg == "Success")
							{
								$('#departmentmasid').empty();
								$('#departmentmasid').append( new Option("-----Select Department-----","",true,false) );
					
								$.each(data.myResult, function(i,response){
									$('#departmentmasid').append( new Option(response.name,response.departmentmasid,true,false) );
								});
							}
							else
							{
								alert(response.msg);
							}
						});		
					});
				}
				$('#usermasid').change(function (){
					var $usermasid = $('#usermasid').val();
					$('#username').focus();
					if($usermasid !="")
					{
						var url="load_user.php?item=UserDetails&itemval="+$usermasid;					
						$.getJSON(url,function(data){
							$.each(data.error, function(i,response){
								if(response.msg == "Success")
								{
									$.each(data.myResult, function(i,response){
										$("#username").val(response.username);
										$("#password").val(response.password);
										$("#lblEmpName").text(response.empname);
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
									alert(response.msg);
									$("#username").val("");
									$("#password").val("");
									$("#lblEmpName").text("");
									$("#active").removeAttr('checked')
								}
							});		
						});
					}
					else
					{
						alert("Please select User");
						$("#username").val("");
						$("#password").val("");
						$("#lblEmpName").text("");
						$("#active").removeAttr('checked')
					}
				});
				
				$("#btnView").click(function(){
					$("form").submit();
					//loadView();
				});
				
				
	$("#btnSave").click(function() {				
		if($("#empmasid option:selected").val()== "")
		{
			alert("Please select Employee");
			return false;
		}
		if(jQuery.trim($("#username").val()) == "")
		{
			alert("Please Enter Username");
			return false;
		}
		if(jQuery.trim($("#password").val()) == "")
		{
			alert("Please Enter password");
			return false;
		}
		var url="save_user.php?action=Save";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$('input[type=text]').val('');
						$('input[type=password]').val('');
						$('#empmasid').val('');
						alert(response.msg);
					}
					else
					{
						$("#cc").html(response.msg)
					}
					
				});
		});
	});
	$("#btnUpdate").click(function() {				
		if($("#usermasid option:selected").val()== "")
		{
			alert("Please select User");
			return false;
		}
		if(jQuery.trim($("#username").val()) == "")
		{
			alert("Please Enter Username");
			return false;
		}
		if(jQuery.trim($("#password").val()) == "")
		{
			alert("Please Enter password");
			return false;
		}
		var url="save_user.php?action=Update";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.error, function(i,response){
					if(response.s =="Success")
					{
						$('input[type=text]').val('');
						$('input[type=password]').val('');
						$('#lblEmpName').text('');
						$('#usermasid').val('');
						$('#empmasid').val('');
						alert(response.msg);
						loadUser();
						loadDept();
					}
					else
					{
						alert(response.s);
					}
				});
		});
	});
	//return false;
				
} );
</script>

</head>
<body id="dt_example">
<form id="myForm" name="myForm" action="" method="post">
<div id="container">
<h1>User Administration</h1>
<div id="menuDiv" width="100%" align="right">
<table>
		<tr>
			<td> <button class="buttonNew" type="button" id="btnNew"> New </button> </td>
			<td> <button class="buttonEdit" type="button" id="btnEdit"> Edit </button> </td>
			<td> <button class="buttonView" type="button" id="btnView"> View </button> </td>
		</tr>
	</table>
</div>
<div id="exampleDiv" width="100%">
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
					<thead>
						<tr>
							<th>Index</th>							
							<th>Employee</th>
							<th>Username</th>
							<th>Department</th>
							<th>Created BY</th>
							<th>Modified BY</th>
							<th>Active</th>
						</tr>
					</thead>
					<tbody id="tbodyContent">
				<?php
					 $sql = "select a.*, b.empname,c.name\n"
					. "case a.active\n"
					. "when \"0\" then \"active\"\n"
					. "when \"1\" then \"disabled\"\n"
					. "end\n"
					. "from mas_user a\n"
					. "inner join mas_employee b on a.empmasid = b.empmasid \n"	
					. "inner join mas_department c on a.departmentmasid = c.departmentmasid \n"					
					. "order by b.empname";
					$sql='select a.*, b.empname, c.* from mas_user a 
	inner join mas_employee b on a.empmasid = b.empmasid 
	left join mas_department c on a.departmentmasid = c.departmentmasid
	order by b.empmasid';
					$result=mysql_query($sql);
					if($result != null) // if $result <> false
					{
						if (mysql_num_rows($result) > 0)
						{
							$i=1;
							   while ($row = mysql_fetch_assoc($result))
								   {
									//echo $row['table_name'];
									$empmasid = $row["empname"];
									$username = $row["username"];
									$departmentname = $row["name"];
									$cby = $row["createdby"];
									$mby = $row["modifiedby"];
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
									<td>".$empmasid."</td>
									<td>".$username."</td>
									<td>".$departmentname."</td>
									<td>".$cby."</td>
									<td>".$mby."</td>
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
							<th>Employee</th>
							<th>Username</th>
							<th>Department</th>
							<th>Created BY</th>
							<th>Modified BY</th>
							<th>Active</th>
						</tr>
					</tfoot>
				</table>
</div>
<span id="cc"></span>
<div id="userDiv">
<table id="usertbl" class="table1" width="60%">
	<thead>
		<tr>
			<th id="tblheader" align="left" colspan="2">
				Create New User	
			</th>
		</tr>
	</thead>
	<tbody>
	<tr>
		<td>
			Company
		</td>
		<td>
			<?php echo($_SESSION['mycompany']);?>
		</td>
	</tr>
	<tr id="selectUserTr">
		<td>
			Select User <font color="red">*</font>
		</td>
		<td>
			<select id="usermasid" name="usermasid">
				<option value="" selected>--Select User--</option>
			</select>
		</td>
	</tr>
	<tr id="selectDepTr">
		<td>
			Select Department <font color="red">*</font>
		</td>
		<td>
			<select id="departmentmasid" name="departmentmasid">
				<option value="" selected>--Select Department--</option>
			</select>
		</td>
	</tr>
	<tr id="selectEmpTr">
		<td>
			Select Employee <font color="red">*</font>
		</td>
		<td>
			<select id="empmasid" name="empmasid">
				<option value="" selected>--Select Employee--</option>
			</select>
		</td>
	</tr>
	<tr id="displayEmpTr">
		<td>
			Employee
		</td>
		<td>
			<label id="lblEmpName"></labe>
		</td>
	</tr>
	<tr>
		<td>
			Username <font color="red">*</font>
		</td>
		<td>
			<input type="text" id="username" name="username">
		</td>
	</tr>
	<tr>
		<td>
			Password <font color="red">*</font>
		</td>
		<td>
			<input type="password" id="password" name="password">
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
			<button type="button" id="btnSave">Create New User</button>
		</td>
	</tr>
	<tr id="editTr">
		<td>
			
		</td>
		<td>
			<button type="button" id="btnUpdate">Update User</button>
		</td>
	</tr>
	</tbody>
</table>
</div>

</div> <!--Main Div-->
</form>
</body>
</html>
