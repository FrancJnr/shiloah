<?php
include('../config.php');
session_start();

$action = $_GET['action'];
$response_array = array();
$active = 0;
//$invoicedesc = $_GET['invoicedesc'];
$invoicedesc = isset($_GET['invoicedesc']) ? $_GET['invoicedesc'] : $_GET['invoicedesc_email'] ;
$vat = isset($_GET['vat']) ? $_GET['vat'] : $_GET['staff'] ;

//$vat = $_GET['vat'];


if (isset($_GET['active']))
{
   // if $_GET['active'] = on
    $active = 1;
}
$m="";
if($action == "Save")
{
	$createdby = $_SESSION['myusername'];
	$custom = array('msg'=> "INSERT INTO invoice_desc (`invoicedesc`, `vat`,`createdby`,`createddatetime`,`active`)  VALUES ('$invoicedesc','$vat','$createdby','$datetime',$active)",'s');
	$sql = "INSERT INTO invoice_desc (`invoicedesc`, `vat`,`createdby`,`createddatetime`,`active`) VALUES ('$invoicedesc','$vat','$createdby','$datetime',$active)";
	$m="Data Saved Successfully";
}
else if($action == "emailsave")
{
	$createdby = $_SESSION['myusername'];
	$departmnt = $_GET['departmnt'];
	$custom = array('msg'=> "INSERT INTO mas_email (`email`, `staff_name`,`created_by`,`created_time`,`active`,`departmentmasid`)  VALUES ('$invoicedesc','$vat','$createdby','$datetime','$active','$departmnt')",'s');
	$sql = "INSERT INTO mas_email (`email`, `staff_name`,`created_by`,`created_time`,`active`,`departmentmasid`)  VALUES ('$invoicedesc','$vat','$createdby','$datetime','$active','$departmnt')";
	$m="Data Saved Successfully";
}
else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
	$invoicedescmasid = $_GET['invoicedescmasid'];
	
	$sql = "UPDATE `invoice_desc` SET `invoicedesc`='$invoicedesc',`vat`='$vat',`modifiedby`='$modifiedby',`modifieddatetime`='$datetime',`active`='$active' WHERE `invoicedescmasid`='$invoicedescmasid'";
	$m="Data Updated Successfully";
}
else if($action == "EmailUpdate")
{
	$modifiedby = $_SESSION['myusername'];
	$invoicedesc_email = $_GET['invoicedesc_email'];
	$emailmasid = $_GET['emailmasid'];
	$departmnt = $_GET['departmnt'];
	$staff = $_GET['staff'];
	$sql = "UPDATE `mas_email` SET `email`='$invoicedesc_email',`modified_by`='$modifiedby',`modified_time`='$datetime',`active`='$active',`staff_name` ='$staff',`departmentmasid`='$departmnt' WHERE `id`='$emailmasid'";
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