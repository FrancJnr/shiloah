<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$response_array = array();
$active = 0;
$module = $_GET['module'];


if (isset($_GET['active']))
{   
    $active = 1;
}
$m="";
if($action == "Save")
{
	$sql = "INSERT INTO mas_module (`modulename`,`active`) VALUES ('$module',$active)";
	$m="Data Saved Successfully";
}else if($action == "Update")
{	
	$modulemasid = $_GET['modulemasid'];
        $sql = "UPDATE `mas_module` SET `modulename`='$module',`active`='$active' WHERE `modulemasid`='$modulemasid'";
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