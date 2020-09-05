<?php
	//$memcache = new Memcache;	
	//$memcache->connect('localhost', 11211) or die ("Could not connect"); 
	////Can Use 127.0.0.1 instead "localhost"
	//$version = $memcache->getVersion();
	//echo "Server's version: ".$version."<br/>\n";
	//$tmp_object = new stdClass;
	//$tmp_object->str_attr = 'test';
	//$tmp_object->int_attr = 123;
	//$memcache->set('key', $tmp_object, false, 10) or die ("Failed to save data at the server");
	//echo "Store data in the cache (data will expire in 10 seconds)<br/>\n";
	//$get_result = $memcache->get('key');
	//echo "Data from the cache:<br/>\n";
	//var_dump($get_result);
	//exit;
	//
	//print_r(get_loaded_extensions());
	////phpinfo();
	//exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<!--META-->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>MEGA Props | Login</title>
	<?php
		include('config.php');
		include('MasterRef.php');
			
		session_start();
		session_destroy();
		
		//$_SESSION['myusername'] = "";
		$client_ip=getIP();
		
		//if($client_ip!="127.0.0.1"){
		//	echo "<br><br><br><center><h1><font color='red'>ACCESS DENIED</font></h1></center><br><br><br><h2>Please contact your system-admin.</h2>";
		//	exit;
		//}
		//else
		//{
		//	echo "Your IP :".$client_ip;
		//}				
	
		function loadcompany()
		{
			$sql = "select companymasid, companyname from mas_company order by companymasid";
			$result = mysql_query($sql);
			var_dump($result);
			if($result != null)
			{
				while($row = mysql_fetch_assoc($result))
				{
					echo("<option value=".$row['companymasid'].">".$row['companyname']."</option>");		
				}
			}
		}
	?>
<!--SCRIPTS-->
<link href="css/login_style.css" rel="stylesheet" type="text/css" />
<!--Slider-in icons-->
<script type="text/javascript">
$(document).ready(function() {
	$("#txtUsername").focus();
	$(".txtUsername").focus(function() {
		$(".user-icon").css("left","-48px");
	});
	$(".txtUsername").blur(function() {
		$(".user-icon").css("left","0px");
	});
	
	$(".txtPassword").focus(function() {
		$(".pass-icon").css("left","-48px");
	});
	$(".txtPassword").blur(function() {
		$(".pass-icon").css("left","0px");
	});		
	$("#btnLogin").click(function() {		
		var usrname = $("#txtUsername").val();
		var pwd = $("#txtPassword").val();
		var cpny = $("#companymasid").val();		
		if(usrname=="")
		{
			alert("Username is Mandatory");
			$("#txtUsername").focus();
			return false;
		}
		if(pwd=="")
		{
			alert("Password is Mandatory");
			$("#txtPassword").focus();
			return false;
		}
		if(cpny==0)
		{
			alert("Company selection is Mandatory");
			$("#companymasid").focus();
			return false;
		}		
		var url="login.php";
		var dataToBeSent = $("form").serialize();		
		$.getJSON(url,dataToBeSent, function(data){
			$.each(data.error, function(i,response){				
				if(response.s =="Success")
				{										
					$("#msg").html("Success");					
				
                               //window.open('Home.php',"_self")
                                }
				else
				{					
					$("#txtUsername").focus();			
					$("#msg").html(response.s);					
				}
			});
		});		
		if($("#msg").html() !="Success")
		{
			return false;
		}
		
	});
//	$("#btnLogin").click(function() {
//		
//	});
});
</script>

</head>
<body>

<!--WRAPPER-->
<div id="wrapper">

	<!--SLIDE-IN ICONS-->
    <div class="user-icon"></div>
    <div class="pass-icon"></div>
    <!--END SLIDE-IN ICONS-->

<!--LOGIN FORM-->
<form class="login-form" action="Home.php" method="post" autocomplete="off">
	<!--HEADER-->
	    <div class="header">
	    <!--TITLE--><h1>Mega ERP Login </h1><!--END TITLE-->	    
	    <!--DESCRIPTION--><span>The Leading Shopping Mall Developers in Kenya.</span><!--END DESCRIPTION-->
	    </div>
	<!--END HEADER-->
		
	<!--CONTENT-->
	    <div class="content">
		<!--USERNAME-->Username:<input name="txtUsername" id="txtUsername" type="text" class="input txtUsername" value="" onfocus="this.value=''" /><!--END USERNAME-->
		<br><br>
	    <!--PASSWORD-->Password:<input name="txtPassword" id="txtPassword" type="password" class="input txtPassword" value="" onfocus="this.value=''" /><!--END PASSWORD-->
	    <br><br>
	    <!--Company-->
				<select id="companymasid" name="companymasid" class="styled-select"> 
					<option value="0" selected style="text-align:center;">--Select Company--</center></option>
					<?php loadcompany();?>
				</select>
	    <!--END COMPANY-->
	     <br><br>
	    <p style="color:red;" id="msg"></p>
	    </div>
	<!--END CONTENT-->
	    
	<!--FOOTER-->
	    <div class="footer">
	    <!--LOGIN BUTTON--><input type="submit" name="btnLogin" id="btnLogin" value="Login" class="button" /><!--END LOGIN BUTTON-->
	    <!--REGISTER BUTTON--><h3><?php		
			$ip; 
			if (getenv("HTTP_CLIENT_IP")) 
			$ip = getenv("HTTP_CLIENT_IP"); 
			else if(getenv("HTTP_X_FORWARDED_FOR")) 
			$ip = getenv("HTTP_X_FORWARDED_FOR"); 
			else if(getenv("REMOTE_ADDR")) 
			$ip = getenv("REMOTE_ADDR"); 
			else 
			$ip = "UNKNOWN";
			echo "IP: ".$client_ip."<br>";	
			echo date('d-m-Y H:i:s');
		?></h3><!--END REGISTER BUTTON-->	    
	    </div>
	<!--END FOOTER-->

</form>
<!--END LOGIN FORM-->
</div>
<!--END WRAPPER-->

<!--GRADIENT--><div class="gradient"></div><!--END GRADIENT-->
<style>
/*     body {
         background: #eee url(images/megaicon.png) repeat center 0px; padding-top:100px;
            background: #eee url(images/ddmenu-bg.jpg) no-repeat center 0px; padding-top:90px;
	    font: 13px 'trebuchet MS', Arial, Helvetica;
            
	}*/
/*        div {
	    background: #eee; 
	    font: 13px 'trebuchet MS', Arial, Helvetica;
            
	}*/
/*.mgimg1{top: 10px;z-index: -1;}*/
/*.mgimg2 {position: absolute;left: 1205px;top: 10px;z-index: -1;}*/
.mpimg {position: absolute;left: 500px;top: 680px;z-index: -1;}
.mcimg {position: absolute;left: 600px;top: 680px;z-index: -1;}
.mmimg {position: absolute;left: 700px;top: 680px;z-index: -1;}
.rcimg {position: absolute;left: 800px;top: 680px;z-index: -1;}
.meimg {position: absolute;left: 900px;top: 680px;z-index: -1;}
.foottxt {position: absolute;left: 680px;top: 770px;z-index: -1;}
</style>
<!--<img src="images/megagroups.png" class ='mgimg1' width='186px' height='146px'>-->
<!--<img src="images/megagroups.png" class ='mgimg2' width='186px' height='146px'>-->
<!--<img src="images/megaplazakisumu.jpg" class ='mpimg' width='70px' height='70px'>
<img src="images/megacitykisumu.jpg" class ='mcimg' width='70px' height='70px'>
<img src="images/megamallkakamega.jpg" class ='mmimg' width='70px' height='70px'>
<img src="images/reliancecentrenairobi.jpg" class ='rcimg' width='70px' height='70px'>
<img src="images/megacentrekitale.jpg" class ='meimg' width='70px' height='70px'>-->
<div class='foottxt'><font color='#0000a0'>&copy; Copyright Mega Properties Group 2016</font></div>
</body>
</html>