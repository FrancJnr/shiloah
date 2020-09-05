<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$response_array = array();

$modulemasid = $_GET['modulemasid'];
$moduleheadermasid = $_GET['moduleheadermasid'];
$filename = $_GET['filename'];
$filepath = $_GET['filepath'];

$m="";
if($action == "Save")
{
	$createdby = $_SESSION['myusername'];	
	$sql = "INSERT INTO mas_module_det (`modulemasid`,`moduleheadermasid`,`filename`, `filepath`,`createdby`,`createddatetime`)
                VALUES ($modulemasid,$moduleheadermasid,'$filename','$filepath','$createdby','$datetime')";
	$m="Data Saved Successfully";       
}else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
	$moduledetmasid = $_GET['moduledetmasid'];
	$sql = "UPDATE `mas_module_det` SET
        `modulemasid` =  $modulemasid,`moduleheadermasid`=$moduleheadermasid,
        `filename`='$filename',`filepath`='$filepath',`modifiedby`='$modifiedby',`modifieddatetime`='$datetime' WHERE `moduledetmasid`='$moduledetmasid'";
	$m="Data Updated Successfully";
        ////$custom = array('msg'=>"error",'s'=>$sql);
        ////$response_array[] = $custom;
        ////echo '{"error":'.json_encode($response_array).'}';
        ////exit;
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