<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$response_array = array();
$active = 0;
$age = $_GET['age'];
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
	$custom = array('msg'=> "INSERT INTO mas_age (`age`, `description`,`createdby`,`createddatetime`,`active`)  VALUES ('$age','$description','$createdby','$datetime',$active)",'s');
	$sql = "INSERT INTO mas_age (`age`, `description`,`createdby`,`createddatetime`,`active`) VALUES ('$age','$description','$createdby','$datetime',$active)";
	$m="Data Saved Successfully";
}else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
	$agemasid = $_GET['agemasid'];
	$sql = "UPDATE `mas_age` SET `age`='$age',`description`='$description',`modifiedby`='$modifiedby',`modifieddatetime`='$datetime',`active`='$active' WHERE `agemasid`='$agemasid'";
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