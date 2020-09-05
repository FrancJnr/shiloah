<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$response_array = array();
$active = 0;
$moduleheader = $_GET['moduleheader'];


if (isset($_GET['active']))
{   
    $active = 1;
}
$m="";
if($action == "Save")
{
	$sql = "INSERT INTO mas_module_header (`moduleheader`) VALUES ('$moduleheader')";
	$m="Data Saved Successfully";
}else if($action == "Update")
{	
	$moduleheadermasid = $_GET['moduleheadermasid'];
        $sql = "UPDATE `mas_module_header` SET `moduleheader`='$moduleheader' WHERE `moduleheadermasid`='$moduleheadermasid'";
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