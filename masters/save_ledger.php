<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$response_array = array();
$active = 0;
$ledger = $_GET['ledger'];
$alias = $_GET['alias'];


if (isset($_GET['active']))
{
   // if $_GET['active'] = on
    $active = 1;
}
$m="";
if($action == "Save")
{
	$createdby = $_SESSION['myusername'];
	$custom = array('msg'=> "INSERT INTO mas_ledger (`ledger`, `alias`,`createdby`,`createddatetime`,`active`)  VALUES ('$ledger','$alias','$createdby','$datetime',$active)",'s');
	$sql = "INSERT INTO mas_ledger (`ledger`, `alias`,`createdby`,`createddatetime`,`active`) VALUES ('$ledger','$alias','$createdby','$datetime',$active)";
	$m="Data Saved Successfully";
}else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
	$ledgermasid = $_GET['ledgermasid'];
	$sql = "UPDATE `mas_ledger` SET `ledger`='$ledger',`alias`='$alias',`modifiedby`='$modifiedby',`modifieddatetime`='$datetime',`active`='$active' WHERE `ledgermasid`='$ledgermasid'";
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