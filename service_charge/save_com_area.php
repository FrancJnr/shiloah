<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$response_array = array();
$active = 0;
$expcomarea = $_GET['expcomarea'];
$expledgermasid = $_GET['expledgermasid'];
$buildingmasid = $_GET['buildingmasid'];

if (isset($_GET['active']))
{
   // if $_GET['active'] = on
    $active = 1;
}
$m="";
if($action == "Save")
{
	$createdby = $_SESSION['myusername'];
	$custom = array('msg'=> "INSERT INTO mas_exp_com_area (`expcomarea`,`expledgermasid`,`createdby`,`createddatetime`,`active`)  VALUES ('$expcomarea','$expledgermasid','$createdby','$datetime',$active)",'s');
	$sql = "INSERT INTO mas_exp_com_area (`buildingmasid`,`expcomarea`,`expledgermasid`,`createdby`,`createddatetime`,`active`) VALUES
	('$buildingmasid','$expcomarea','$expledgermasid','$createdby','$datetime',$active)";
	$m="Data Saved Successfully";
}else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
	$expcomareamasid = $_GET['expcomareamasid'];
	$sql = "UPDATE `mas_exp_com_area` SET `buildingmasid`='$buildingmasid',`expcomarea`='$expcomarea',
	`expledgermasid`='$expledgermasid',`modifiedby`='$modifiedby',`modifieddatetime`='$datetime',`active`='$active' WHERE `expcomareamasid`='$expcomareamasid'";
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