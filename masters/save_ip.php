<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$response_array = array();
$active = 0;

$username = $_GET['username'];
$companymasid = $_GET['companymasid'];
$ipno = $_GET['ipno'];
$systemname = $_GET['systemname'];
$invfilepath = $_GET['invfilepath'];

if (isset($_GET['active']))
{
   // if $_GET['active'] = on
    $active = 1;
}
$m="";
if($action == "Save")
{
	$createdby = $_SESSION['myusername'];
	$custom = array('msg'=> "INSERT INTO mas_ip (`username`,`companymasid`,`ipno`,`systemname`,`invfilepath`,`createdby`,`createddatetime`,`active`) VALUES('$username','$companymasid','$ipno','$systemname','$invfilepath','$createdby','$datetime',$active)",'s');
	$sql = "INSERT INTO mas_ip (`username`,`companymasid`,`ipno`,`systemname`,`invfilepath`,`createdby`,`createddatetime`,`active`) VALUES
	('$username','$companymasid','$ipno','$systemname','$invfilepath','$createdby','$datetime',$active)";
	$m="Data Saved Successfully";
        //$custom = array('msg'=>$sql,'s'=>"Success");
        //$response_array[] = $custom;
        //echo '{"error":'.json_encode($response_array).'}';
        //exit;
}else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
	$ipmasid = $_GET['ipmasid'];
	$sql = "UPDATE `mas_ip` SET `ipno`='$ipno',
                `companymasid`='$companymasid',`username`='$username',`systemname`='$systemname',`invfilepath`='$invfilepath',`modifiedby`='$modifiedby',`modifieddatetime`='$datetime',`active`='$active' WHERE `ipmasid`='$ipmasid'";
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