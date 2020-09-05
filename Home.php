<?php
 setcookie("recentLinks", "");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>MEGA Props | Home Page</title>
</head>
<?php
include('config.php');
include('MasterRef.php');
session_start();
if(!isset($_SESSION['myusername']))     
{
  header("location:index.php");
} 
$_SESSION['fiscalyear']="";
$_SESSION['acyearmasid']="";
$_SESSION['mycompanymasid'] = $_POST['companymasid'];

$qry="select * from mas_company where companymasid ='".$_SESSION['mycompanymasid']."' and active ='1'";
$result=mysql_query($qry);
if($result != null) // if $result <> null
{
	if (mysql_num_rows($result) > 0)
	{
		 while ($row = mysql_fetch_assoc($result))
		{
			$_SESSION['mycompany'] = $row["companyname"];
			$_SESSION['mycompanycode'] = $row["companycode"];
			
			$qry="select * from mas_acyear where companymasid ='".$_SESSION['mycompanymasid']."' and active ='1'";
			$result=mysql_query($qry);
			if($result != null) // if $result <> null
			{
				if (mysql_num_rows($result) > 0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						$acyearfrom = $row["acyearfrom"];
						$_SESSION['acyearmasid'] = $row["acyearmasid"];
						if(strtotime($acyearfrom) != 0)
						{
							$acyearfrom = date_format(new DateTime($acyearfrom), "d-F-Y");
							//E.g. Fri 03-August-2012 13:51:37
						}
						else
						{
							$acyearfrom="";
						}
						$acyearto = $row["acyearto"];
						if(strtotime($acyearto) != 0)
						{
							$acyearto = date_format(new DateTime($acyearto), "d-F-Y");
						}
						else
						{
							$acyearto = "";
						}
						$_SESSION['fiscalyearfrom'] = $acyearfrom;
						$_SESSION['fiscalyearto'] = $acyearto;
						$_SESSION['fiscalyear'] = $acyearfrom ."  to  ".$acyearto;
					}
				}
				else
				{
					$_SESSION['fiscalyearfrom'] = "0";
					$_SESSION['fiscalyearto'] = "0";
					$_SESSION['fiscalyear'] = "0";
				}
			}
		}
	}
}



//if ( (! isset($_SESSION['myusername']) ) and (! isset($_SESSION['fiscalyear'])) ){
if($_SESSION['myusername'] =="" or $_SESSION['fiscalyear'] == "")
{
	header("location:index.php");
}
else
{
	header("location:menu.php");
}
?>
<!--<frameset rows="14%,*" frameborder="0" framespacing="0">
	<frame id="top" name="topframe" src="homeTop.php" scrolling="no">
		<frameset cols="15%,*,10%">
		<frame id="left" name="leftframe" src="homeLeft.php" frameborder="0" framespacing="0" scrolling="Yes" >
		<frame id="middle" name="content" src="homeMiddle.php" frameborder="0" framespacing="0"scrolling="auto" noresize>
		<iframe src="myURL" width="300" height="300" frameBorder="0">Browser not compatible.</iframe>
		<frame id="right" name="rightFrame" src="homeRight.php" frameborder="0" framespacing="0" scrolling="no" noresize>

	</frameset>
<noframes>-->	
<frameset rows="12%,*" frameborder="0" framespacing="0">
	<frame id="top" name="topframe" src="homeTop.php" scrolling="no">
	<frame id="left" name="leftframe" src="homeLeft.php" frameborder="0" framespacing="0" scrolling="Yes" >
<noframes>
<body>This browser does not support frames


</body>
</noframes>
</frameset>
</html>
