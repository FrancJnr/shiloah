<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$response_array = array();
$active = 0;
$shoptype = $_GET['shoptype'];
$description = $_GET['description'];


if (isset($_GET['active']))
{
   // if $_GET['active'] = on
    $active = 1;
}
$m="";
if($action == "Save")
{
	$createdby = $_SESSION['myusername'];
	$custom = array('msg'=> "INSERT INTO mas_shoptype (`shoptype`, `description`,`createdby`,`createddatetime`,`active`)  VALUES ('$shoptype','$description','$createdby','$datetime',$active)",'s');
	$sql = "INSERT INTO mas_shoptype (`shoptype`, `description`,`createdby`,`createddatetime`,`active`) VALUES ('$shoptype','$description','$createdby','$datetime',$active)";
	$m="Data Saved Successfully";
}else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
	$shoptypemasid = $_GET['shoptypemasid'];
	$sql = "UPDATE `mas_shoptype` SET `shoptype`='$shoptype',`description`='$description',`modifiedby`='$modifiedby',`modifieddatetime`='$datetime',`active`='$active' WHERE `shoptypemasid`='$shoptypemasid'";
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