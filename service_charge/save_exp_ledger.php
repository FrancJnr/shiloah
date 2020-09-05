<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$response_array = array();
$active = 0;
$expledger = $_GET['expledger'];
$expgroupmasid = $_GET['expgroupmasid'];


if (isset($_GET['active']))
{
   // if $_GET['active'] = on
    $active = 1;
}
$m="";
if($action == "Save")
{
	$createdby = $_SESSION['myusername'];
	$custom = array('msg'=> "INSERT INTO mas_exp_ledger (`expledger`,`expgroupmasid`,`createdby`,`createddatetime`,`active`)  VALUES ('$expledger','$expgroupmasid','$createdby','$datetime',$active)",'s');
	$sql = "INSERT INTO mas_exp_ledger (`expledger`,`expgroupmasid`,`createdby`,`createddatetime`,`active`) VALUES
	('$expledger','$expgroupmasid','$createdby','$datetime',$active)";
	$m="Data Saved Successfully";
}else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
	$expledgermasid = $_GET['expledgermasid'];
	$sql = "UPDATE `mas_exp_ledger` SET `expledger`='$expledger',`expgroupmasid`='$expgroupmasid',`modifiedby`='$modifiedby',`modifieddatetime`='$datetime',`active`='$active' WHERE `expledgermasid`='$expledgermasid'";
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