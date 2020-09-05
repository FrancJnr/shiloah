<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$response_array = array();
$acyearfrom = date('Y-m-d', strtotime($_GET['acyearfrom']));
$acyearto= date('Y-m-d', strtotime($_GET['acyearto']));
$remarks = $_GET['remarks'];
$active = 1;
if(!isset($_GET['active']))
{
   //if ($_GET['active'] == "on")
   $active =0;
}
$companymasid = $_SESSION['mycompanymasid'];
$acyearmasid = $_GET['acyearmasid'];
$m="";
if($action == "Save")
{
        $createdby = $_SESSION['myusername'];
        If($active == "1")
        {
            mysql_query(" UPDATE `mas_acyear` SET `active`='0' WHERE companymasid = $companymasid"); // update active 0 to make this default a/c year
        }
        $sql ="INSERT INTO `mas_acyear`(`acyearfrom`, `acyearto`, `companymasid`, `remarks`, `createdby`, `createddatetime`, `active`) VALUES('$acyearfrom','$acyearto','$companymasid','$remarks','$createdby','$datetime',$active)";
	$custom = array('msg'=> $sql,'s');
	$m="Data Saved Successfully";
}else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
        If($active == "1")
        {
            mysql_query(" UPDATE `mas_acyear` SET `active`='0' WHERE companymasid = $companymasid"); // update active 0 to make this default a/c year
        }
	$sql = "UPDATE `mas_acyear` SET `acyearfrom`='$acyearfrom',`acyearto`='$acyearto',`remarks`='$remarks',`modifiedby`='$modifiedby',`modifieddatetime`='$datetime',`active`='$active' WHERE `acyearmasid`='$acyearmasid'";
        $custom = array('msg'=> $sql,'s');
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