<?php
include('../config.php');
session_start();
$action = $_GET['action'];
$response_array = array();
$empmasid = $_GET['empmasid'];
$deptmasid = $_GET['departmentmasid'];
$usermasid = $_GET['usermasid'];
$username = $_GET['username'];
$password = $_GET['password'];
$companymasid = $_SESSION['mycompanymasid'];
$active = 0;
if (isset($_GET['active']))
{   
    $active = 1;
}

if($action == "Save")
{
    $createdby = $_SESSION['myusername']; 
	$sqlcheck = "SELECT * FROM `mas_user` WHERE empmasid = '$empmasid' ";
	$result = mysql_query($sqlcheck);
	$number=mysql_num_rows($result);
	//echo $number;
	//die ();
	if($number == 0)
	{
    $sql = "INSERT INTO mas_user (`empmasid`, `username`, `password`,`departmentmasid`,`createdby`,`createddatetime`,`active`) VALUES ('$empmasid','$username','$password','$deptmasid','$createdby','$datetime','$active')";
    $result = mysql_query($sql);
	if($result == false)
{
    $custom = array('msg'=>$action,'s'=>mysql_error());
}
else
{
    $custom = array('msg'=>"Data Saved Successfully",'s'=>"Success");    
}
	}
	else{
		$custom = array('msg'=>"This Employee is Already added as a user ",'s'=>"Success");  
	}
}else if($action == "Update")
{
    $modifiedby = $_SESSION['myusername'];
    $sql = "UPDATE `mas_user` SET `username`='$username',`password`='$password', `departmentmasid`='$deptmasid',`modifiedby`='$modifiedby',`modifieddatetime`='$datetime',`active`='$active' WHERE `usermasid`='$usermasid'";
	$result = mysql_query($sql);
	if($result == false)
{
    $custom = array('msg'=>$action,'s'=>mysql_error());
}
else
{
    $custom = array('msg'=>"Data Saved Successfully",'s'=>"Success");    
}
	}
//echo $sql;
//die();


$response_array[] = $custom;
echo '{
	"error":'.json_encode($response_array).
    '}';
?>