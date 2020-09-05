<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>MEGA Props | Login</title>
	<?php
		include('MasterRef.php');
		session_start();
		session_destroy();
		$_SESSION['myusername'] = "";
		$client_ip=getIP();
		echo "Your IP :".$client_ip;
		
		function getIP() { 
			$ip; 
			if (getenv("HTTP_CLIENT_IP")) 
			$ip = getenv("HTTP_CLIENT_IP"); 
			else if(getenv("HTTP_X_FORWARDED_FOR")) 
			$ip = getenv("HTTP_X_FORWARDED_FOR"); 
			else if(getenv("REMOTE_ADDR")) 
			$ip = getenv("REMOTE_ADDR"); 
			else 
			$ip = "UNKNOWN";
			return $ip; 
		}
		////$my_report = "C://expiry_list.rpt";
		////try 
		////{ 
		////    $ObjectFactory  = new COM ( 'CrystalReports14.ObjectFactory.1' );
		////    $crapp = $ObjectFactory->CreateObject("CrystalDesignRunTime.Application");
		////    $creport = $crapp->OpenReport($my_report, 1);
		////} 
		////catch ( exception $e ) 
		////{ 
		////    echo 'caught exception: ' . $e->getMessage () . ', error trace: ' . $e->getTraceAsString (); 
		////}  
		
	?>
<script type="text/javascript">

$(document).ready(function(){
	$('input[type="text"]').focus(function() {
		 $(this).addClass("focus");
	});
	$('input[type="text"]').blur(function() {
	    $(this).removeClass("focus");
	});
	$("#login").slideDown('fast');
	$("#company").hide();
	$("#txtUsername").focus();
	$('#slectCompany').html("");
	$("#btnLogin").click(function() {
		var username = $("#txtUsername").val();
		var url="login.php";
		var dataToBeSent = $("form").serialize();
		$.getJSON(url,dataToBeSent, function(data){
			$.each(data.error, function(i,response){
				if (response.s != "Success")
				{
					$("#msgPanel").html(user.msg);
					$("#txtUsername").focus();
				}
				else
				{
					$("#msgPanel").html("");
					$("#login").slideUp('fast', function() {
						$("#company").slideDown('fast');
						$("#slectCompany").focus();
						$("#nameTd").append(username);
					});
				}
			});
			$.each(data.myResult, function(i,user){
							
				var comp = user.companyname +" ("+user.companycode +")";
				$('#slectCompany').append( new Option(comp,user.companymasid,false,false) );	
			});
		});
		return false;
	});
	
	$("#btnGoToHome").click(function() {
		if($('#slectCompany option:selected').val() =="")
		{
			jAlert("Please Select a Company")
			return false;	
		}
		else
		{
			$('#hiddenCompanyMasId').val($('#slectCompany option:selected').val());	
		}
	});
});

</script>
<style>
	  body { 
                background:#c1c1e1;
		background: url(images/Mega_Mall.jpg) no-repeat center center fixed; 
                 -webkit-background-size: cover; 
                 -moz-background-size: cover; 
                 -o-background-size: cover; 
                 background-size: cover; 
                 filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.earth_1920X1200.jpg', sizingMethod='scale'); 
                 -ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='earth_1920X1200.jpg', sizingMethod='scale')"; 
                 width: 100%; 
                 height: 100%; */
           } 
</style>
</head>
<body>
<h1>Mega Properties Group - PMS</h1>
<hr>
<br><br>
<table>
<tr>
<td>
<br><br><br><br><br><br>
<form id="frmLogin">
<div id="login" style='width: 410px;'>
	<p><font color="Red" style="font-weight: bold;">Login</font></p>
	<table border ="0" cellpadding='2' width='100%'>
				<td>Username &nbsp;<font color="red">*</font></td>
				<td><input type="text" id="txtUsername" name="txtUsername"></td>
			</tr>
			<tr>
				<td>Password &nbsp;<font color="red">*</font></td>
				<td><input type="password" id="txtPassword" name="txtPassword"></td>
			</tr>
			<tr>
				<td>&nbsp</td>
				<td><button id="btnLogin" name="btnLogin">Login</button></td>
			</tr>
			<tr>
				<td colspan=2><div id="message"><p style="color:red;" id="msgPanel"></p></div></td>
			</tr>
	</table>
</div>
</form>
<form id="frmCompany" action="Home.php" method="POST">
<div id="company" style='width: 410px;'>
		<table border ="0">
			<tr>
				<td id="nameTd" colspan=2>Welcome </td>
			</tr>
			<tr>
				<td colspan=2></td>
			</tr>
			<tr>
				<td>Company *</td>
				<td>					
					<select id="companymasid" width="100%">
						<option val="" selected>-------Select Company--------</option>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<button id="btnGoToHome" name="btnGoToHome">Home >></button>
					<a href="index.php">Logout</a>
				</td>
			</tr>
		</table>
		<input type="hidden" name="hiddenCompanyMasId" id="hiddenCompanyMasId" >
</div>
</form>
</td>
</tr>
</table>
</body>
</html>