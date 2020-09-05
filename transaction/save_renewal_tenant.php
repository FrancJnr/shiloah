<?php
include('../config.php');
session_start();
$response_array = array();
$action = $_GET['action'];
$companymasid = $_SESSION['mycompanymasid'];
$grouptenantmasid = $_GET['grouptenantmasid'];
$createdby = $_SESSION['myusername'];

$sqlupd="";$sqlins="";$sqlinsdet="";$sqlmas="";$sqldet="";$tenanmasidmain="";
$iidtenant=0;$iidtenantcp=0;
$i=0;$iidgroupmas=0;$nos="";

try{

$sql ="select c.tenantmasid from group_tenant_mas a
	inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
	inner join mas_tenant c on c.tenantmasid = b.tenantmasid
	where a.grouptenantmasid=$grouptenantmasid";
	
$result = mysql_query($sql);



if ($result != false)
{
    if ($action=="renew")
    {
       
	while($row = mysql_fetch_assoc($result))
	{
	    $tenantmasid = $row['tenantmasid'];	    
	
	    $sqlchk = "select tenantmasid from mas_tenant where tenantmasid=$tenantmasid and tenantmasid not in
			(select tenantmasid from rec_tenant);";
	    $res = mysql_query($sqlchk);
	    $numrow = mysql_num_rows($res);
	    if( $numrow > 0)
	    {
		// //copy tenant from mas_tenant
		$sqlins ="insert into mas_tenant
		    (tenanttypemasid, salutation,leasename, tradingname, tenantcode, companymasid, buildingmasid, blockmasid, floormasid, shopmasid, shoptypemasid, orgtypemasid, nob, agemasidlt, agemasidrc, agemasidcp, creditlimit, latefeeinterest, doo, doc, pin, regno, address1, address2, city, state, pincode, country, poboxno, telephone1, telephone2, fax, emailid, website, remarks, cpname1, cpdesignation1, cpnid1, cpphone1, cpname2, cpdesignation2, cpnid2, cpphone2, createdby, createddatetime, modifiedby, modifieddatetime, active)
		    select 
		    tenanttypemasid, salutation,leasename, tradingname, tenantcode, companymasid, buildingmasid, blockmasid, floormasid, shopmasid, shoptypemasid, orgtypemasid, nob, agemasidlt, agemasidrc, agemasidcp, creditlimit, latefeeinterest, doo, doc, pin, regno, address1, address2, city, state, pincode, country, poboxno, telephone1, telephone2, fax, emailid, website, remarks, cpname1, cpdesignation1, cpnid1, cpphone1, cpname2, cpdesignation2, cpnid2, cpphone2, createdby, createddatetime, modifiedby, modifieddatetime, active
		    from mas_tenant where tenantmasid=$tenantmasid;";		
		mysql_query($sqlins);
		$iidtenant = mysql_insert_id();
		
		// //copy tenant cp contact person
		$sqlinsdet="INSERT INTO mas_tenant_cp
			    (`tenantmasid`,`cpname`,`cptypemasid`,`cpnid`,`cpmobile`,`cplandline`,`cpemailid`,`documentname`)
			    select  `tenantmasid`,`cpname`,`cptypemasid`,`cpnid`,`cpmobile`,`cplandline`,`cpemailid`,`documentname`
			    from mas_tenant_cp where tenantmasid=$tenantmasid and documentname='1';";
		mysql_query($sqlinsdet);
		$iidtenantcp = mysql_insert_id();
		
		  // // update tenantmasid in tenant_cp table
		$sqlupd ="update mas_tenant_cp set tenantmasid = $iidtenant where tenantcpmasid= $iidtenantcp;";
		mysql_query($sqlupd);
		
		// // update tenant renewal from id
		$sqlupd ="update mas_tenant set renewalfromid=$tenantmasid ,shopoccupied='1' where tenantmasid= $iidtenant;";
		mysql_query($sqlupd);
		
		// // update tenant renewal status
		$sqlupd ="update mas_tenant set renewal = '1' where tenantmasid= $tenantmasid;";
		mysql_query($sqlupd);
		
		
		
	    }
	    else
	    {
		// //copy tenant from mas_tenant
		$sqlins ="insert into mas_tenant
		    (tenanttypemasid, salutation,leasename, tradingname, tenantcode, companymasid, buildingmasid, blockmasid, floormasid, shopmasid, shoptypemasid, orgtypemasid, nob, agemasidlt, agemasidrc, agemasidcp, creditlimit, latefeeinterest, doo, doc, pin, regno, address1, address2, city, state, pincode, country, poboxno, telephone1, telephone2, fax, emailid, website, remarks, cpname1, cpdesignation1, cpnid1, cpphone1, cpname2, cpdesignation2, cpnid2, cpphone2, createdby, createddatetime, modifiedby, modifieddatetime, active)
		    select 
		    tenanttypemasid, salutation,leasename, tradingname, tenantcode, companymasid, buildingmasid, blockmasid, floormasid, shopmasid, shoptypemasid, orgtypemasid, nob, agemasidlt, agemasidrc, agemasidcp, creditlimit, latefeeinterest, doo, doc, pin, regno, address1, address2, city, state, pincode, country, poboxno, telephone1, telephone2, fax, emailid, website, remarks, cpname1, cpdesignation1, cpnid1, cpphone1, cpname2, cpdesignation2, cpnid2, cpphone2, createdby, createddatetime, modifiedby, modifieddatetime, active
		    from rec_tenant where tenantmasid=$tenantmasid;";		
		mysql_query($sqlins);
		$iidtenant = mysql_insert_id();
		// //copy tenant cp
		$sqlinsdet="INSERT INTO mas_tenant_cp
			    (`tenantmasid`,`cpname`,`cptypemasid`,`cpnid`,`cpmobile`,`cplandline`,`cpemailid`,`documentname`)
			    select  `tenantmasid`,`cpname`,`cptypemasid`,`cpnid`,`cpmobile`,`cplandline`,`cpemailid`,`documentname`
			    from rec_tenant_cp where tenantmasid=$tenantmasid and documentname='1';";
		mysql_query($sqlinsdet);
		$iidtenantcp = mysql_insert_id();
		
		  // // update tenantmasid in tenant_cp table
		$sqlupd ="update mas_tenant_cp set tenantmasid = $iidtenant where tenantcpmasid= $iidtenantcp;";
		mysql_query($sqlupd);
		
		// // update tenant renewal from id
		$sqlupd ="update mas_tenant set renewalfromid=$tenantmasid ,shopoccupied='1' where tenantmasid= $iidtenant;";
		mysql_query($sqlupd);
		
		// // update tenant renewal status
		$sqlupd ="update rec_tenant set renewal = '1' where tenantmasid= $tenantmasid;";
		mysql_query($sqlupd);
	    }

	    if($i ==0)
	    {
		// //insert group tenant mas
		$sqlmas  = "INSERT INTO `group_tenant_mas`(`tenantmasid`,`createdby`, `createddatetime`) VALUES ";
		$sqlmas .= " ('$iidtenant','$createdby','$datetime');";
		mysql_query($sqlmas);
		$iidgroupmas = mysql_insert_id();
		
		// //insert group tenant det once
		$sqldet  = "INSERT INTO `group_tenant_det`(`grouptenantmasid`, `tenantmasid`) VALUES ";
		$sqldet .= " ('$iidgroupmas','$iidtenant');";
		mysql_query($sqldet);
		$i++;
	    }
	    else
	    {
		// //insert group tenant det rest
		$sqldet = "INSERT INTO `group_tenant_det`(`grouptenantmasid`, `tenantmasid`) VALUES ";
		$sqldet .= " ('$iidgroupmas','$iidtenant');";
		mysql_query($sqldet);
	    }
	    
	    $gpmasid=0;
	    $sqlgp ="select grouptenantmasid from group_tenant_det where tenantmasid=$tenantmasid;";
	    $resultgp = mysql_query($sqlgp);
	    if($resultgp != null)
	    {
		$rowgp = mysql_fetch_assoc($resultgp);
		$gpmasid=$rowgp['grouptenantmasid'];
		//insert into waiting list
		//$sqlwaitinglist  ="insert into waiting_list (grouptenantmasid) values ('$gpmasid')";
		//mysql_query($sqlwaitinglist);
	    }
	    
	    ////insert into waiting list
	    //$sqlwaitinglist  ="insert into waiting_list (grouptenantmasid) values ('$iidgroupmas')";
	    //mysql_query($sqlwaitinglist);
	}
	////$custom = array('msg'=> $sqlins.$sqlmas.$sqldet ,'s'=>'Success');
	$custom = array('msg'=>"Renewal Success" ,'s'=>'Success');
	$response_array[] = $custom;
	echo '{"error":'.json_encode($response_array).'}';
	exit;
    
    }
    else if ($action=="cancel")
    {	
	while($row = mysql_fetch_assoc($result))
	{
	    $tenantmasid = $row['tenantmasid'];
	    $editpermission =0;
	    $sqlchk ="select editpermission from trans_offerletter where tenantmasid =$tenantmasid;";
	    $res = mysql_query($sqlchk);
	    while ($row = mysql_fetch_assoc($res))
	    {
		$editpermission= $row['editpermission'];
	    }
	    
	    if ( $editpermission ==0) // check offerletter finalized or not if not delete else throw exception
	    {
		$sqlselect="select renewalfromid from mas_tenant where tenantmasid =$tenantmasid;";
		$res = mysql_query($sqlselect);
		while ($row = mysql_fetch_assoc($res))
		{
		    $renewalfromid = $row['renewalfromid'];
		}
		 // // activate renewal tenant offerletter
		$sqlupd ="update trans_offerletter set editpermission ='1' where tenantmasid= $renewalfromid;";
		mysql_query($sqlupd);
		
		// //del renewed tenant from mas_tenant
		$sqldel ="delete from mas_tenant where tenantmasid=$tenantmasid;";		
		mysql_query($sqldel);
		
		// //del renewed tenant cp
		$sqldel ="delete from mas_tenant_cp where tenantmasid=$tenantmasid;";		
		mysql_query($sqldel);
		
		// //del renewed tenant from rec_tenant
		$sqldel ="delete from rec_tenant where tenantmasid=$tenantmasid;";		
		mysql_query($sqldel);
		
		// //del renewed tenant cp
		$sqldel ="delete from rec_tenant_cp where tenantmasid=$tenantmasid;";		
		mysql_query($sqldel);
	    
		// //del renewed tenant offerletter
		$sqldel ="delete from trans_offerletter where tenantmasid=$tenantmasid;";		
		mysql_query($sqldel);
		
		// //del renewed tenant offerletter
		$sqldel ="delete from rec_trans_offerletter where tenantmasid=$tenantmasid;";		
		mysql_query($sqldel);
		
		// //del renewed tenant rpt offerletter
		$sqldel ="delete from rpt_offerletter where tenantmasid=$tenantmasid;";		
		mysql_query($sqldel);
		
		// //del renewed tenant rpt lease
		$sqldel ="delete from rpt_lease where tenantmasid=$tenantmasid;";		
		mysql_query($sqldel);		
		
		
		// del from waiting list
		$grouptenantmasid=0;
		$s = "select grouptenantmasid from group_tenant_det where tenantmasid = $tenantmasid;";
		$r = mysql_query($s);
		if($r !=null)
		{
		    $rw = mysql_fetch_assoc($r);
		    $grouptenantmasid = $rw['grouptenantmasid'];
		    // //del renewed tenant group det
		    $sqldel ="delete from waiting_list where grouptenantmasid=$grouptenantmasid;";		
		    mysql_query($sqldel);
		}				
		
		
		//if($i=0)
		//{
		//    // //del renewed tenant group mas
		//    $sqldel ="delete from group_tenant_mas where tenantmasid=$tenantmasid;";		
		//    mysql_query($sqldel);
		//    $i++;
		//}		
		//    // //del renewed tenant group det
		//    $sqldel ="delete from group_tenant_det where tenantmasid=$tenantmasid;";		
		//    mysql_query($sqldel);
		    
		    
		    
		    ////$custom = array('msg'=> $sqlupd.$sqlins.$sqlmas.$sqldet ,'s'=>'Success');
		    $custom = array('msg'=>"Canceled Success" ,'s'=>'Success');
		    $response_array[] = $custom;
		    echo '{"error":'.json_encode($response_array).'}';
		    exit;
	    }
	    else
	    {
		$custom = array('msg'=>"Sorry !!! Offerletter finalized cant cancel renewal." ,'s'=>'Success');
		$response_array[] = $custom;
		echo '{"error":'.json_encode($response_array).'}';
		exit;
	    }
	}   
    
    }
}

}
catch (Exception $err)
{
	$custom = array(
		    'msg'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
		    's'=>'Success');
	$response_array[] = $custom;
	echo '{"error":'.json_encode($response_array).'}';
	exit;
}
?>