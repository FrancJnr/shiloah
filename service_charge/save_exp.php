<?php
include('../config.php');
session_start();

//$sqlArray="";
//$cnt =1;
//	foreach ($_GET as $k=>$v) {
//	    //$k = preg_replace('/[^a-z]/i', '', $k); 
//	    $sqlArray.= $cnt."--> KEY: ".$k."; VALUE: ".$v."<BR>";
//	    $cnt++;
//	}
//$custom = array('result'=> $sqlArray ,'s'=>'error');
//	$response_array[] = $custom;
//	echo '{"error":'.json_encode($response_array).'}';
//	exit;

try{    
    $companymasid = $_SESSION['mycompanymasid'];
    $buildingmasid = $_GET['buildingmasid'];
    $action = $_GET['action'];
    
    
    $fromdate = "01 ".$_GET['fromdate'];    
    $todate = "01 ".$_GET['todate'];       

    $fromdate = date('Y-m-d', strtotime($fromdate));
    $todate = date('Y-m-d', strtotime($todate));
    
    $tablemas ="trans_exp_mas";$iid=0;$sqlmas="";$sqldet="";$rcount=0;
    $tabledet ="trans_exp_det";
 
    if($action =="save")
    {
	// ---> check start
	$sql= "select * from trans_exp_mas where buildingmasid='$buildingmasid' and
		'$fromdate' between fromdate and todate";	
	$result = mysql_query($sql);
	if($result !=null)
	{
	    $rcount = mysql_num_rows($result);
	}    
	if($rcount >0)
	{
	    $custom = array('result'=> "Alert: Expense already available for the Period.!!!",'s'=>'error');
	    $response_array[] = $custom;
	    echo '{"error":'.json_encode($response_array).'}';
	    exit;
	}
	$sql= "select * from trans_exp_mas where buildingmasid='$buildingmasid' and
		'$todate' between fromdate and todate";	
	$result = mysql_query($sql);
	if($result !=null)
	{
	    $rcount = mysql_num_rows($result);
	}    
	if($rcount >0)
	{
	    $custom = array('result'=> "Alert: Expense already available for the Period.!!!",'s'=>'error');
	    $response_array[] = $custom;
	    echo '{"error":'.json_encode($response_array).'}';
	    exit;
	}
	// ---> check end	
    }
    else  if($action =="update")
    {
	// ---> check start
	$transexpmasid = $_GET['hidtransexpmasid'];
	$sql= "delete from trans_exp_mas where transexpmasid ='$transexpmasid'";	
	$result = mysql_query($sql);
	// ---> check end
    }
    
    foreach($_GET as $key=>$val)
	{             	    
	    if($key =="buildingmasid")
	    {
		$cols[] = $key;
		$vals[] = "'".$val."'";
		
		$cols[] = "fromdate";
		$vals[] = "'".$fromdate."'";
		
		$cols[] = "todate";
		$vals[] = "'".$todate."'";
	    }else if ($key =="totalamount")
	    {
		$cols[] = $key;
		$vals[] = "'".str_replace(",","",$val)."'";		
		$cols[] = "createdby";
		$vals[] ="'". $_SESSION['myusername']."'";
		$cols[] = "createddatetime";
		$vals[] = "'".$datetime."'";
		$sqlmas = 'INSERT INTO `'.$tablemas.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
		
		$result = mysql_query($sqlmas);		
		if($result == false)
		{                
		    $custom = array('result'=> mysql_error(),'s'=>'error');
		    $response_array[] = $custom;
		    echo '{"error":'.json_encode($response_array).'}';
		    exit;
		}
		else
		{
		    $iid = mysql_insert_id();
		    $sqlgen = "select * from trans_exp_gen_mas where transexpmasid='$transexpmasid'";
		    $resultgen = mysql_query($sqlgen);
		    $rcountgen=0;
		    if($resultgen)
		    {
			$rcountgen=mysql_num_rows($resultgen);
			if($rcountgen>=1)
			{
			    mysql_query("update trans_exp_gen_mas set transexpmasid=$iid where transexpmasid ='$transexpmasid'");
			}
		    }
		}
	    }
	}
	$cols="";
	$vals="";
	foreach($_GET as $key=>$val)
	{
	    $sno = strstr($key, '_', true);
	    $expledgermasid = strstr($key, '_', true);	    
	    $amount = strstr($key, '_', true);
	    if($sno !="sno")
	    {
		if($expledgermasid =="expledgermasid")
		{
		    $cols[] = "transexpmasid";
		    $vals[] = "'".$iid."'";
		    
		    $cols[] = "expledgermasid";
		    $vals[] = "'".$val."'";
		}
		else if($amount =="amount")
		{
		    $cols[] = "amount";
		    if($val=="")
			$val=0;
		    
		    $vals[] = "'".str_replace(",","",$val)."'";		
		    
		    $sqldet = 'INSERT INTO `'.$tabledet.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
		    
		    $cols="";
		    $vals="";
		    if($iid <=0)
		    {                    
			$custom = array('result'=> "Un expected error while inserting data in to $tabledet",'s'=>'error');
			$response_array[] = $custom;
			echo '{"error":'.json_encode($response_array).'}';
			exit;
		    }
		    else
		    {
			mysql_query($sqldet);
		    }     
		}
	    }
	}
    
    //$custom = array('result'=> $sqlmas."<br>".$sqldet,'s'=>'error');
    $custom = array('result'=> "<font color='green'>Data Saved Successfully !!!",'s'=>'error');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
    exit;
}
catch (Exception $err)
{
    $custom = array(
                'divContent'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
                's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
?>