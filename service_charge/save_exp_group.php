<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$response_array = array();
$active = 0;
$expgroup = $_GET['expgroup'];
$exptype = $_GET['exptype'];

if (isset($_GET['active']))
{
   // if $_GET['active'] = on
    $active = 1;
}
$m="";
if($action == "Save")
{
	$createdby = $_SESSION['myusername'];
	$custom = array('msg'=> "INSERT INTO mas_exp_group (`expgroup`, `exptype`,`createdby`,`createddatetime`,`active`)  VALUES ('$expgroup','$exptype','$createdby','$datetime',$active)",'s');
	$sql = "INSERT INTO mas_exp_group (`expgroup`,`exptype`,`createdby`,`createddatetime`,`active`) VALUES ('$expgroup','$exptype','$createdby','$datetime',$active)";
	$m="Data Saved Successfully";
}else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
	$expgroupmasid = $_GET['expgroupmasid'];
	$sql = "UPDATE `mas_exp_group` SET `expgroup`='$expgroup',`exptype`='$exptype',`modifiedby`='$modifiedby',`modifieddatetime`='$datetime',`active`='$active' WHERE `expgroupmasid`='$expgroupmasid'";
	$m="Data Updated Successfully";
}
$result = mysql_query($sql);
if($result == false)
{
    $custom = array('msg'=>mysql_error(),'s'=>$sql);
}
else
{
    $custom = array('msg'=>$m,'s'=>"Success");    
}
$response_array[] = $custom;
echo '{
	"error":'.json_encode($response_array).
    '}';
?>