<?php
include('../config.php');
session_start();
$companymasid = $_SESSION['mycompanymasid'];
//$sqlGet ="";
//$nk =0;
//foreach ($_GET as $k=>$v) {
//    $sqlGet.= $nk."; Name: ".$k."; Value: ".$v."<BR>";
//    $nk++;
//}
//$custom = array('msg'=> $sqlGet ,'s'=>'error');
//$response_array[] = $custom;
//echo '{"error":'.json_encode($response_array).'}';
//exit;

try{    
    
    $action = $_GET['action'];    
    $enquirymasid = $_GET['hidenquirymasid'];
    $flpdt1 = date('Y-m-d', strtotime($_GET['flpdt1']));
    $flpremarks = $_GET['flpremarks'];
    $flpdt2 = date('Y-m-d', strtotime($_GET['flpdt2']));
    $flpstatus = $_GET['flpstatus'];
    
    $user = $_SESSION['myusername'];
    $msg="";
    if($action == "Save_flp")
    {        
        $sql = "insert into mas_enquiry_det (enquirymasid,flpdt1,flpremarks,flpdt2,flpstatus,createdby,createddatetime)
                    values('$enquirymasid','$flpdt1','$flpremarks','$flpdt2','$flpstatus','$user','$datetime')";
        $msg = "<font color='green'>Data Saved Successfully.";
    }
    else if($action == "Update_flp")
    {
        $enquirydetmasid = $_GET['enquirydetmasid'];
        $sql = "update mas_enquiry_det set flpdt1 ='$flpdt1',flpremarks='$flpremarks',flpdt2='$flpdt2',flpstatus='$flpstatus',
                modifiedby='$user',modifieddatetime='$datetime' where enquirydetmasid = $enquirydetmasid;";
        $msg = "<font color='green'>Data Updated Successfully.";
    }
    
    ////$custom = array('msg'=> $sql ,'s'=>'error');
    ////$response_array[] = $custom;
    ////echo '{"error":'.json_encode($response_array).'}';
    ////exit;
    
    $result = mysql_query($sql);    
    if($result == false)
    {
        $custom = array('msg'=>mysql_error(),'s'=>$sql);
    }
    else
    {
        $custom = array('msg'=>$msg,'s'=>"Success");        
    }
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    exit;
    
}//try
catch (Exception $err)
{
    $custom = array('msg'=>"Error: ".$err->getMessage().", Line No:".$err->getLine()
                    ,'s'=>"Error");    
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
?>