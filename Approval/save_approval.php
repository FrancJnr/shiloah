<?php
include('../config.php');
session_start();
$response_array = array();
$action = $_GET['action'];
$companymasid = $_SESSION['mycompanymasid'];
$grouptenantmasid = $_GET['grouptenantmasid'];
$nos="";
$sql ="select a.tenantmasid, b.offerlettermasid from group_tenant_det a
	inner join trans_offerletter b on b.tenantmasid = a.tenantmasid
	where a.grouptenantmasid = $grouptenantmasid";
$result = mysql_query($sql);


if ($action=="finalize")
{

if ($result != false)
{
	while($row = mysql_fetch_assoc($result))
	{
	    $nos .= $row['offerlettermasid']." , ";
	    $sqlupdate = "update trans_offerletter set editpermission = 1 where offerlettermasid=".$row['offerlettermasid'];	    
	    mysql_query($sqlupdate);
	    
	    //////$sqlupdate = "update mas_tenant set active = 1 where tenantmasid=".$row['tenantmasid'];
	    //////mysql_query($sqlupdate);
	}
	$custom = array('msg'=> "Tenant Finalaized" ,'s'=>'Success');
	$response_array[] = $custom;
	echo '{"error":'.json_encode($response_array).'}';
	exit;
}

}
else if ($action=="cancel")
{
     if ($result != false)
{
	while($row = mysql_fetch_assoc($result))
	{
	
		$nos .= $row['offerlettermasid']." , ";
		
		$custom = array('msg'=>"<font color='red'> Alert: Offerletter canceled Successfully. </font>",'s'=>"Success");	
		
		// offer letter can be canceled if it is in waiting list 
		$sql1 = "select a.offerlettermasid,b.grouptenantmasid from trans_offerletter a 
				inner join group_tenant_mas b on b.tenantmasid = a.tenantmasid 
				where a.offerlettermasid=".$row['offerlettermasid']." and b.grouptenantmasid in
				(select grouptenantmasid from waiting_list);";
		$result1 = mysql_query($sql1);
		if($result1 != null)
		{
			$num = mysql_num_rows($result1);
			$row1 = mysql_fetch_assoc($result1);
			if($num > 0 )
			{
				//delete offerletter
				//$sqldelofflet = "delete from trans_offerletter where offerlettermasid=".$row1['offerlettermasid'];				
				//mysql_query($sqldelofflet);
				
				//delete from waiting list
				//$sqldelofflet = "delete from waiting_list where grouptenantmasid=".$row1['grouptenantmasid'];				
				//mysql_query($sqldelofflet);
				
				////to open for edit
				$sqlupdate = "update trans_offerletter set editpermission = 0 where offerlettermasid=".$row['offerlettermasid'];
				mysql_query($sqlupdate);
			}
			else
			{
				$custom = array('msg'=>"<font color='red'>Alert:  Running tenant cant be canceled. Contact your Operation Manager.</font>",'s'=>"error");	
			}
		}
		
		////$custom = array('msg'=>$sqldelofflet,'s'=>"Success");	
		$response_array[] = $custom;
		echo '{"error":'.json_encode($response_array).'}';
		exit;
		
		//////$sqlupdate = "update trans_offerletter set editpermission = 0 where offerlettermasid=".$row['offerlettermasid'];
		//////mysql_query($sqlupdate);
		//////
		//////$sqlupdate = "update mas_tenant set active = 0 where tenantmasid=".$row['tenantmasid'];
		//////mysql_query($sqlupdate);
	}		
}

}
?>