<?php
include('../config.php');
session_start();

//$sqlArray="";
//$cnt =1;
//	foreach ($_GET as $k=>$v) {	    
//	    $sqlArray.= $cnt."--> KEY: ".$k."; VALUE: ".$v."<BR>";
//	    $cnt++;
//	}
//$custom = array('msg'=> $sqlArray ,'s'=>'Success');
//	$response_array[] = $custom;
//	echo '{"error":'.json_encode($response_array).'}';
//	exit;
try{
    
    $action = $_GET['action'];
    if($action == "Update"){
        $modifiedby = $_SESSION['myusername'];
        $tenantmasid = $_GET['tenantmasid'];
	$buildingmasid = $_GET['buildingmasid'];
	$floormasid = $_GET['floormasid'];
	$blockmasid = $_GET['blockmasid'];
	$shopmasid = $_GET['shopmasid'];
        $pin = $_GET['pin'];
        $regno = $_GET['regno'];
        $address1 = $_GET['address1'];
        $address2 = $_GET['address2'];
        $city = $_GET['city'];
        $state = $_GET['state'];
        $pincode = $_GET['pincode'];
        $country = $_GET['country'];
        $poboxno = $_GET['poboxno'];
        $telephone1 = $_GET['telephone1'];
        $telephone2 = $_GET['telephone2'];
        $fax = $_GET['fax'];
        $emailid = $_GET['emailid'];
        $website = $_GET['website'];
        $remarks = $_GET['remarks'];
        
        //mas_tenant
        $sql ="update mas_tenant set buildingmasid='$buildingmasid',floormasid='$floormasid',blockmasid='$blockmasid',shopmasid='$shopmasid',
		pin='$pin',regno='$regno',address1='$address1',address2='$address2',city='$city',state='$state',
                pincode='$pincode',country='$country',poboxno='$poboxno',telephone1='$telephone1',telephone2='$telephone2',
                fax='$fax',emailid='$emailid',website='$website',remarks='$remarks'  where tenantmasid = '$tenantmasid';";
        $result = mysql_query($sql);
        
        //rec_tenant
        $sql ="update rec_tenant set buildingmasid='$buildingmasid',floormasid='$floormasid',blockmasid='$blockmasid',shopmasid='$shopmasid',
		pin='$pin',regno='$regno',address1='$address1',address2='$address2',city='$city',state='$state',
                pincode='$pincode',country='$country',poboxno='$poboxno',telephone1='$telephone1',telephone2='$telephone2',
                fax='$fax',emailid='$emailid',website='$website',remarks='$remarks'  where tenantmasid = '$tenantmasid';";
        $result = mysql_query($sql);
        
        if($result == false)
	{
	    $custom = array('msg'=>mysql_error(),'s'=>$sql);
	}
	else
	{
	    $custom = array('msg'=>"<font color='green'>Data Saved Successfully</font>",'s'=>"Success");	
	}
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';
        exit;
    }
}
catch (Exception $err)
{
    $custom = array('msg'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),'s'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    exit;
}