<?php
include('../config.php');
session_start();
try{
	
//$sqlArray="";
//$cnt =1;
//	foreach ($_GET as $k=>$v) {
//	    //$k = preg_replace('/[^a-z]/i', '', $k); 
//	    $sqlArray.= $cnt."--> KEY: ".$k."; VALUE: ".$v."<BR>";
//	    $cnt++;
//	}
//$custom = array('msg'=>$sqlArray,'s'=>'error');	
//	$response_array[] = $custom;
//	echo '{"error":'.json_encode($response_array).'}';
//	exit;
	
$action = $_GET['action'];
$companymasid = $_SESSION['mycompanymasid'];

if($action == "Save")
{
$tenantmasid = $_GET['tenantmasid'];
$leasename="";$buildingname="";$newleasename = $_GET['newleasename'];$message="";
$sql = "select a.leasename , b.buildingname from mas_tenant a inner join mas_building b on b.buildingmasid = a.buildingmasid where a.tenantmasid = $tenantmasid;";
$result = mysql_query($sql);
if($result !=null)
{
    $row = mysql_fetch_assoc($result);
    $leasename = $row['leasename'];
    $buildingname= $row['buildingname'];
}

$createdby = $_SESSION['myusername'];
$ins = "INSERT INTO rec_tenant 
	(tenantmasid,salutation,tenanttypemasid, leasename, tradingname, tenantcode, companymasid, buildingmasid, blockmasid,
	floormasid, shopmasid, shoptypemasid, orgtypemasid, nob, agemasidlt, agemasidrc, agemasidcp, creditlimit,
	latefeeinterest, doo, doc, pin, regno, address1, address2, city, state, pincode, country, poboxno, telephone1,
	telephone2, fax, emailid, website, remarks, cpname1, cpdesignation1, cpnid1, cpphone1, cpname2, cpdesignation2,
	cpnid2, cpphone2, createdby, createddatetime, modifiedby, modifieddatetime, active,renewal,renewalfromid,shopoccupied
	,includedby,includedon)
	select * from mas_tenant where tenantmasid= $tenantmasid and active='1';";
	
$iid ="0";
$result = mysql_query($ins);
$iid = mysql_insert_id();

//$updrec ="Update rec_tenant set
//		leasename = '".$_GET['newleasename'] ."',
//		shoptypemasid = ".$_GET['shoptypemasid'] .",
//		orgtypemasid = ".$_GET['orgtypemasid'] .",
//		tradingname = '".$_GET['tradingname'] ."',
//		nob = '".$_GET['nob'] ."',
//		agemasidlt = ".$_GET['agemasidlt'] .",
//		agemasidrc = ".$_GET['agemasidrc'] .",
//		createdby = '".$createdby ."',
//		createddatetime = '".$datetime ."',
//		modifiedby = '',
//		modifieddatetime = ''
//		where rectenantmasid = '$iid'";
$updrec ="Update rec_tenant set
		leasename = '".$_GET['newleasename'] ."',
		shoptypemasid = ".$_GET['shoptypemasid'] .",
		orgtypemasid = ".$_GET['orgtypemasid'] .",
		tradingname = '".$_GET['tradingname'] ."',
		nob = '".$_GET['nob'] ."',		
		agemasidrc = ".$_GET['agemasidrc'] .",
		createdby = '".$createdby ."',
		createddatetime = '".$datetime ."',
		modifiedby = '',
		modifieddatetime = ''
		where rectenantmasid = '$iid'";

$result = mysql_query($updrec);
$sqlDet="";
	foreach($_GET as $key=>$val)
	{
	    $key = preg_replace('/[^a-z]/i', '', $key);
	   
	    if($key =="cpname")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
	    }
	    elseif($key =="cptypemasid")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
	    }
	    elseif($key =="cpnid")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
	    }
	    elseif($key =="cpmobile")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
	    }
	     elseif($key =="cplandline")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
	    }
	    elseif($key =="documentname")
	    {
		$cols[] = "".$key."";
		$vals[] = "'1'";
	    }
	     elseif($key =="cpemailid")
	    {
		$cols[] = "".$key."";
		$vals[] = "'".$val."'";
		$cols[] = "rectenantmasid";
		$vals[] = "'".$iid."'";
		$cols[] = "tenantmasid";
		$vals[] = "'".$tenantmasid."'";
		$sqlDet .= 'INSERT INTO `rec_tenant_cp`('.implode($cols, ',').') VALUES('.implode($vals, ',').')'.";";
		$cols="";
		$vals="";
	    }	        
	}

	////$custom = array('msg'=>$sqlDet,'s'=>'Success');
	////$response_array[] = $custom;
	////echo '{"error":'.json_encode($response_array).'}';
	////exit;
	
	if($sqlDet !="")
	{
	    $sqlExec = explode(";",$sqlDet);
	    for($i=0;$i<count($sqlExec);$i++)
	    {
	         if($sqlExec[$i] != "")
	         {
		  	$result = mysql_query($sqlExec[$i]); 
		}
	    }
	}
	
	$updmas ="Update mas_tenant set active ='0', shopoccupied='0' where tenantmasid ='$tenantmasid';";
	$result = mysql_query($updmas);
	
	////$custom = array('msg'=>$ins."</br>".$updrec."</br>".$sqlDet."</br>".$updmas,'s'=>'error');	
	$message .="<b><u>Rectification Details:</u></b><br><br>";
	// ORIGINAL DETIALS
	$message .= "<table width='100%' cellspacing='2' cellpadding='2' border='1' style='font-size : 77%;font-family : 'Myriad Web',Verdana,Helvetica,Arial,sans-serif;background : #efe none; color : #630;'>";
	$message .= "<tr align='center' bgcolor='gold'><th colspan='7'>ORIGINAL DETAILS - $buildingname</th></tr>";
	$message .= "<tr align='center' bgcolor='gold'>		   
		    <th>LEASENAME</th>
		    <th>SHOP TYPE</th>
		    <th>ORG TYPE</th>
		    <th>BUSINESS</th>
		    <th>RENT CYCLE</th>
		    <th>CREATED BY</th>
		    <th>DATE</th></tr>";
	$sqlmast = "select leasename,shoptype,orgtype,nob,age,a.createdby,date_format(a.createddatetime,'%d-%m-%Y') as createddatetime from mas_tenant a
		    inner join mas_shoptype b on b.shoptypemasid = a.shoptypemasid
		    inner join mas_orgtype c on c.orgtypemasid = a.orgtypemasid
		    inner join mas_age e on e.agemasid =a.agemasidrc
		    where tenantmasid ='$tenantmasid';";
	$result = mysql_query($sqlmast);
	if($result)
	{
	    while($row = mysql_fetch_assoc($result))
	    {
		$message .= "<tr>";
		$message .= "<td align='center'>".$row["leasename"]."</td>";
		$message .= "<td align='center'>".$row["shoptype"]."</td>";
		$message .= "<td align='center'>".$row["orgtype"]."</td>";
		$message .= "<td align='center'>".$row["nob"]."</td>";
		$message .= "<td align='center'>".$row["age"]."</td>";
		$message .= "<td align='center'>".$row["createdby"]."</td>";
		$message .= "<td align='center'>".$row["createddatetime"]."</td>";
		$message .= "</tr>";
	    }
	}
	$message .= "<tr align='center' bgcolor='gold'><th colspan='5'>RECTIFIED DETIALS</th><th>RECTIFIED BY</th><th>DATE</th></tr>";
	// RECTIFIED DETIALS
	$sqlmast = "select leasename,shoptype,orgtype,nob,age,a.createdby,date_format(a.createddatetime,'%d-%m-%Y') as createddatetime from rec_tenant a
		    inner join mas_shoptype b on b.shoptypemasid = a.shoptypemasid
		    inner join mas_orgtype c on c.orgtypemasid = a.orgtypemasid
		    inner join mas_age e on e.agemasid =a.agemasidrc
		    where tenantmasid ='$tenantmasid';";
	$result = mysql_query($sqlmast);
	if($result)
	{
	    while($row = mysql_fetch_assoc($result))
	    {
		$message .= "<tr>";
		$message .= "<td align='center'>".$row["leasename"]."</td>";
		$message .= "<td align='center'>".$row["shoptype"]."</td>";
		$message .= "<td align='center'>".$row["orgtype"]."</td>";
		$message .= "<td align='center'>".$row["nob"]."</td>";
		$message .= "<td align='center'>".$row["age"]."</td>";
		$message .= "<td align='center'>".$row["createdby"]."</td>";
		$message .= "<td align='center'>".$row["createddatetime"]."</td>";
		$message .= "</tr>";
	    }
	}
	$message .="</table>";
	//**************************** EMAIL *************************//
	if($message !="")
	{
	    $address = "ronald-finance@shiloahmega.com";	    
	    $address = "prabakaran-accounts@shiloahmega.com";	    
	    $subject = "TENANCY RECTIFICATION DETAILS";   
	    $mail->AddAddress($address, "Receipent");
$sql12 = "SELECT email,staff_name FROM `mas_email` WHERE departmentmasid IN('1','9','4','7') AND active = '1'";
$result12=mysql_query($sql12);
$recipients = array();
		while($row12 = mysql_fetch_array($result12 ))
    {
   $recipients[$row12['email']] = $row12['staff_name']; 
	}		
	    // $recipients = array(				   
		// 'arulraj@shiloahmega.com' => 'Arul Raj',
		// 'marketing@shiloahmega.com' => 'Muthu Kumar',
		// 'dipak@shiloahmega.com' => 'Dipak',
		// 'mitesh@shiloahmega.com' => 'Mitehsh',
		// 'juma@shiloahmega.com' => 'Prabhu'
	    // );  
		
	    foreach($recipients as $email => $name)
	    {
	       $mail->AddCC($email, $name);
	    }
	    $mail->Subject    = $subject;
	    $mail->MsgHTML($message);	    
	    if(!$mail->Send())
	    {		     
		$custom = array('msg'=> $mail->ErrorInfo,'s'=>'error');	   
	    } else
	    {
		$custom = array('msg'=> "<font color='green'> TENANCY RECTIFICATION DETAILS sent to ".$address."</font>",'s'=>'Success');		    
	    }
	}
	else
	{
	    $custom = array('msg'=>"Data Saved Successfully",'s'=>'Success');	
	}
    //**************************** EMAIL *************************//    	
	$response_array[] = $custom;
	echo '{"error":'.json_encode($response_array).'}';
	exit;
}
else if($action == "Update")
{
$tenantmasid = $_GET['rectenantmasid'];
$modifiedby = $_SESSION['myusername'];

	//$updrec ="Update rec_tenant set
	//	leasename = '".$_GET['newleasename'] ."',
	//	shoptypemasid = ".$_GET['shoptypemasid'] .",
	//	orgtypemasid = ".$_GET['orgtypemasid'] .",
	//	tradingname = '".$_GET['tradingname'] ."',
	//	nob = '".$_GET['nob'] ."',
	//	agemasidlt = ".$_GET['agemasidlt'] .",
	//	agemasidrc = ".$_GET['agemasidrc'] .",		
	//	modifiedby = '".$modifiedby ."',
	//	modifieddatetime = '".$datetime ."'
	//	where tenantmasid = '$tenantmasid' and active='1' and shopoccupied='1';";
	$updrec ="Update rec_tenant set
		leasename = '".$_GET['newleasename'] ."',
		shoptypemasid = ".$_GET['shoptypemasid'] .",
		orgtypemasid = ".$_GET['orgtypemasid'] .",
		tradingname = '".$_GET['tradingname'] ."',
		nob = '".$_GET['nob'] ."',		
		agemasidrc = ".$_GET['agemasidrc'] .",		
		modifiedby = '".$modifiedby ."',
		modifieddatetime = '".$datetime ."'
		where tenantmasid = '$tenantmasid' and active='1' and shopoccupied='1';";

$result = mysql_query($updrec);
$sqlDet="";
	foreach($_GET as $key=>$val)
	{
		$key = preg_replace('/[^a-z]/i', '', $key);
	       
		if($key =="cpname")
		{
		    $cols[] = "".$key."";
		    $vals[] = "'".$val."'";
		}
		elseif($key =="cptypemasid")
		{
		    $cols[] = "".$key."";
		    $vals[] = "'".$val."'";
		}
		elseif($key =="cpnid")
		{
		    $cols[] = "".$key."";
		    $vals[] = "'".$val."'";
		}
		elseif($key =="cpmobile")
		{
		    $cols[] = "".$key."";
		    $vals[] = "'".$val."'";
		}
		 elseif($key =="cplandline")
		{
		    $cols[] = "".$key."";
		    $vals[] = "'".$val."'";
		}
		elseif($key =="documentname")
		{
		    $cols[] = "".$key."";
		    $vals[] = "'1'";
		}
		 elseif($key =="cpemailid")
		{
		    $cols[] = "".$key."";
		    $vals[] = "'".$val."'";		   
		    $cols[] = "tenantmasid";
		    $vals[] = "'".$tenantmasid."'";
		    $sqlDet .= 'INSERT INTO `rec_tenant_cp`('.implode($cols, ',').') VALUES('.implode($vals, ',').')'.";";
		    $cols="";
		    $vals="";
		}	        
	}
	    $sqlDel ="delete from rec_tenant_cp where tenantmasid ='$tenantmasid';";
	    mysql_query($sqlDel);
	    if($sqlDet !="")
	    {
		$sqlExec = explode(";",$sqlDet);
		for($i=0;$i<count($sqlExec);$i++)
		{
		     if($sqlExec[$i] != "")
		     {
			    $result = mysql_query($sqlExec[$i]); 
		    }
		}
	    }
		    
	    //$custom = array('msg'=>$updrec.$sqlDel.$sqlDet,'s'=>'error');
	    $custom = array('msg'=>"Data Updated Successfully",'s'=>'Success');
	    $response_array[] = $custom;
	    echo '{"error":'.json_encode($response_array).'}';
	    exit;
}


}
catch (Exception $err)
{
	$custom = array(
            'msg'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
            's'=>'Success');
	$response_array[] = $custom;
	echo '{"error":'.json_encode($response_array).'}';
}	
?>