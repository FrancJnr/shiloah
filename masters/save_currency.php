<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$response_array = array();
$active = 0;
$currencyname = $_GET['currencyname'];
$country = $_GET['country'];
if (isset($_GET['active']))
{
   // if $_GET['active'] = on
    $active = 1;
}
$m="";
if($action == "Save")
{
	$createdby = $_SESSION['myusername'];
	$custom = array('msg'=> "INSERT INTO mas_currency (`currencyname`, `country`,`createdby`,`createddatetime`,`active`)  VALUES ('$currencyname','$country','$createdby','$datetime',$active)",'s');
	$sql = "INSERT INTO mas_currency (`currencyname`, `country`,`createdby`,`createddatetime`,`active`) VALUES ('$currencyname','$country','$createdby','$datetime',$active)";
	$m="Data Saved Successfully";
}else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
	$currencymasid = $_GET['currencymasid'];
	$sql = "UPDATE `mas_currency` SET `currencyname`='$currencyname',`country`='$country',`modifiedby`='$modifiedby',`modifieddatetime`='$datetime',`active`='$active' WHERE `currencymasid`='$currencymasid'";
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