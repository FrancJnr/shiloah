<?php
header("Content-type: text/xml");
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
try
{    
    $sqldel="";
    $sql1 ="";
    $sql2 ="";
    $tenanmasidmain=0;
    $tenanmasidchild=0;        
    $nk =1;$cn =1;$iid ="0";
    $createdby = $_SESSION['myusername'];
    $action = $_GET['action'];
    if ($action == "group")
    {
	foreach ($_GET as $k=>$v) {
            //print_r($_GET);
	    
	    if ($nk >=4)
	    {
		if (strlen($k) >= 11)
		{
		    $g = str_split($k,11);
		    
		    if($g[0] =="tenantmasid")
		    {
			if ($cn == 1)
			{
			    $tenanmasidmain = $v;
			    $sql1.= "INSERT INTO `group_tenant_mas`(`tenantmasid`,`createdby`, `createddatetime`) VALUES ";
			    $sql1.= " ('$tenanmasidmain','$createdby','$datetime');";
			    $result = mysql_query($sql1);
			    $iid = mysql_insert_id();
			    $sql2.= "INSERT INTO `group_tenant_det`(`grouptenantmasid`, `tenantmasid`) VALUES ";
			    $sql2.= " ('$iid','$tenanmasidmain');";
			    $sql3  ="insert into waiting_list (grouptenantmasid) values ('$iid')";
			    mysql_query($sql3);
			}
			else
			{
			    $tenanmasidchild = $v;
			    $sql2.= "INSERT INTO `group_tenant_det`(`grouptenantmasid`, `tenantmasid`) VALUES ";
			    $sql2.= " ('$iid','$tenanmasidchild');";
			}
			$cn++;	
		    }
		}
	    }
	    $nk++;
	}    
	$sqlArray[] = $sql2;
	$sqlExec = explode(";",$sqlArray[0]);
	for($i=0;$i<count($sqlExec);$i++)
	{
	    if($sqlExec[$i] != "")
	    {
		$result = mysql_query($sqlExec[$i]); 
	    }
	}
	if($result == false)
	{
	    $custom = array('msg'=>mysql_error(),'s'=>$sql);
	}
	else
	{
	    $custom = array('msg'=>"Data Grouped Successfully",'s'=>"Success");    
	}
	//$custom = array('msg'=> $sql1.$sql2 ,'s'=>'error');
	
    }
    else if ($action == "ungroup")
    {
	foreach ($_GET as $k=>$v) {
	    
	    if ($nk >=1)
	    {
		if ($k == "grouptenantmasid")
		{
		    $sqldel="Delete from `group_tenant_mas` where grouptenantmasid =$v";
		    $result = mysql_query($sqldel);
		    $sqldel="Delete from `rpt_offerletter` where grouptenantmasid =$v";
		    $result = mysql_query($sqldel);
		    $sqldel="Delete from `rpt_lease` where grouptenantmasid =$v";
		    $result = mysql_query($sqldel);
		    $sqldel="Delete from `waiting_list` where grouptenantmasid =$v";
		    $result = mysql_query($sqldel);
		}
	    }
	    $nk++;
	}    	
	if($result == false)
	{
	    $custom = array('msg'=>mysql_error(),'s'=>$sql);
	}
	else
	{
	    $custom = array('msg'=>"Data Saved Un-Grouped Successfully",'s'=>"Success");
	    //$custom = array('msg'=> $sqldel.$sql1.$sql2 ,'s'=>'error');
	}
    }
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    
}
catch (Exception $err)
{
$custom = array(
            'divContent'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
            's'=>'Success');
$response_array[] = $custom;
echo '{"error":'.json_encode($response_array).'}';
exit;
}
?>