<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>jQuery PHP Json Response</title>
	<?php
		include('MasterRef.php');
		session_start();
		session_destroy();
	?>
<script type="text/javascript">

$(document).ready(function(){
	$("#btnLogin").click(function() {
		var url="test.php";
		var dataToBeSent = $("form").serialize();
		$("#userdata tbody").html("");
		$.getJSON(url,dataToBeSent, function(data){
				$.each(data.members, function(i,user){
				var tblRow =
				"<tr>"
				+"<td>"+user.companymasid+"</td>"
				+"<td>"+user.companyname+"</td>"
				+"<td>"+user.companycode+"</td>"
				+"<td>"+user.active+"</td>"
				+"</tr>" ;
				$(tblRow).appendTo("#userdata tbody");
			});
				$.each(data.peoples, function(i,user){
				var tblRow =
				"<tr>"
				+"<td>"+user.name+"</td>"
				+"</tr>" ;
				$(tblRow).appendTo("#userdata tbody");
			});
		});
		return false;
	});
});

</script>
</head>
<body>
	<form>
		<div id="msg">
			<table id="userdata" border="1">
				<thead>
				<th>Id</th>
				<th>Company Name</th>
				<th>Code</th>
				<th>Active</th> 
				</thead>
				<tbody></tbody>
			</table>
			<input type="hidden" id="txtUsername" name="txtUsername" value="administrator">
		</div>
		<input type="Submit" id="btnLogin" name="btnLogin" value="  Login  ">
	</form>
</body>
</html>