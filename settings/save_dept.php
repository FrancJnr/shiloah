<?php
include('../config.php');
session_start();
$action = $_GET['action'];
$response_array = array();
$deptmasid = $_GET['departmentmasid'];
$name = $_GET['name'];

$companymasid = $_SESSION['mycompanymasid'];
$active = 0;
if (isset($_GET['active']))
{   
    $active = 1;
}
if($action == "Save")
{
    $createdby = $_SESSION['myusername'];    
    $sql = "INSERT INTO mas_department(`name`,`createdby`,`createddatetime`) VALUES ('$name','$createdby','$datetime')";
   // die($sql);
}else if($action == "Update")
{
    $modifiedby = $_SESSION['myusername'];
    $sql = "UPDATE `mas_department` SET `name`='$name' WHERE `departmentmasid`='$deptmasid'";
}

$result = mysql_query($sql);

if($result == false)
{
    $custom = array('msg'=>$action,'s'=>mysql_error());
}
else
{
    $custom = array('msg'=>"Data Saves Successfully",'s'=>"Success");    
}
$response_array[] = $custom;
echo '{
	"error":'.json_encode($response_array).
    '}';
?>