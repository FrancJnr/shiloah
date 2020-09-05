<?php
include('../config.php');
session_start();    
try
{    
    $action = $_GET['action'];
    $response_array = array();       
    $active = 0;$m="";
        
    $grouptenantmasid = $_GET['hid_itemid'];    
    
    $createddate = explode("-",$_GET['createddate']);
    $createddate = $createddate[2]."-".$createddate[1]."-".$createddate[0];
    
    $graceperiod = $_GET['graceperiod'];
    $paymentfor = $_GET['paymentfor'];
    
    $subject = $_GET['subject'];
    $para1 = $_GET['para1'];
    $para2 = $_GET['para2'];
    
    $outstandingamt = $_GET['outstandingamt'];
    
    $expirydate = explode("-",$_GET['expirydate']);
    $expirydate = $expirydate[2]."-".$expirydate[1]."-".$expirydate[0];
    
    $modifiedby = $_SESSION['myusername'];
    
    if (isset($_GET['active']))
    {   
        $active = 1;
    }
    if($action == "Save")
    {	
        $refdistressmasid=$_GET['refdistressmasid'];		 
	$iid=0;
	//insert into rpt_distress
	$sql = "INSERT INTO rpt_distress (`grouptenantmasid`,`createddate`,`graceperiod`,`paymentfor`,`subject`,`para1`,`para2`,`outstandingamt`,`expirydate`,`modifiedby`,`modifieddatetime`,`active`) VALUES
                ('$grouptenantmasid','$createddate','$graceperiod','$paymentfor','$subject','$para1','$para2','$outstandingamt','$expirydate','$modifiedby','$datetime',$active);";        		
	$result = mysql_query($sql);
	if($result == false)
	{
	    $custom = array('msg'=>"<font color='red'>".mysql_error()."</font>",'s'=>$sql);
	}
	else
	{
	    $iid = mysql_insert_id();
	    if($refdistressmasid ==0)
		$refdistressmasid = $iid;
	    
	    $sqlchk = "select * from rpt_distress_det where grouptenantmasid = $grouptenantmasid;";
	    $re1 = mysql_query($sqlchk);
	    if($re1 !=null)
	    {
		$rc1 = mysql_num_rows($re1);
		if($rc1 <=0)
		{
		    // insert into rpt_distress_det with default ='1'
		    $sqldet = "insert into rpt_distress_det (`distressmasid`,`grouptenantmasid`,`defaultid`,`refdistressmasid`) values ($iid,$grouptenantmasid,'1',$refdistressmasid);";
		    mysql_query($sqldet);
		}
		else
		{
		    // insert into rpt_distress_det with default='0'
		    $sqldet = "insert into rpt_distress_det (`distressmasid`,`grouptenantmasid`,`defaultid`,`refdistressmasid`) values ($iid,$grouptenantmasid,'0',$refdistressmasid);";
		    mysql_query($sqldet);
		}
	    }
	    ////$custom = array('msg'=>$sqlchk.'<br>'.$sqldet,'s'=>"Error");    
	    ////$response_array[] = $custom;
	    ////echo '{"error":'.json_encode($response_array).'}';
	    ////exit;
	    
	    $m ="<font color='green'>Data Saved Successfully</font>";
	    if($re1 == false)
	    {
		$custom = array('msg'=>"<font color='red'>".mysql_error()."</font>",'s'=>$sql);
	    }
	    else
	    {
		$custom = array('msg'=>$m,'s'=>"Success");    
	    }
	}
	$response_array[] = $custom;
	echo '{"error":'.json_encode($response_array).'}';
	exit;
	
    }else if($action == "Update")
    {	
        $distressmasid = $_GET['hiddistressmasid'];
        $sql = "UPDATE `rpt_distress` SET 
                `grouptenantmasid`='$grouptenantmasid',`createddate`='$createddate',`graceperiod`='$graceperiod',
		`paymentfor`='$paymentfor',`subject`='$subject',`para1`='$para1',`para2`='$para2',
                `outstandingamt`='$outstandingamt',`expirydate`='$expirydate',`createddate`='$createddate',
		`modifiedby`='$modifiedby',`modifieddatetime`='$datetime',
                `active`='$active' WHERE `distressmasid`='$distressmasid';";
        $m="<font color='maroon'>Data Updated Successfully</font>";
	$result = mysql_query($sql);
	if($result == false)
	{
	    $custom = array('msg'=>"<font color='red'>".mysql_error()."</font>",'s'=>$sql);
	}
	else
	{
	    $custom = array('msg'=>$m,'s'=>"Success");    
	}
	$response_array[] = $custom;
	echo '{"error":'.json_encode($response_array).'}';
	exit;
    }    
    
   
}
catch (Exception $err)
{
    $response_array = array();
    $custom = array('msg'=>$err->getMessage(),'s'=>"Error");    
    $response_array[] = $custom;
    echo '{
	"error":'.json_encode($response_array).
    '}';    
}
?>