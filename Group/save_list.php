<?php
header("Content-type: text/xml");
include('../config.php');
session_start();
$companymasid = $_SESSION['mycompanymasid'];

//    $sqlGet ="";
//    $nk =0;
//    foreach ($_GET as $k=>$v) {
//	$sqlGet.= $nk."; Name: ".$k."; Value: ".$v."<BR>";
//	$nk++;
//    }
//    $custom = array('msg'=> $sqlGet ,'s'=>'error');
//    $response_array[] = $custom;
//    echo '{"error":'.json_encode($response_array).'}';
//    exit;
$result = false;
try
{
    $nk =1;
    $action = $_GET['action'];
    $grouptenantmasid="";
    $pinno="";
    if ($action == "include")
    {
        foreach ($_GET as $k=>$v) {
	    
	    if ($nk >=1)
	    {
		if ($k == "tenantmasid")
		{
		    $grouptenantmasid = $v;
		}
		else if ($k == "pinno")
		{
		    $pinno = $v;
		    $tenantmasid="";
		    $shopmasid ="";
		    // check for shop availability
		    
		    $sql1 = "select a.shopmasid,a.tenantmasid,a.leasename,a.buildingmasid from mas_tenant a 
			    inner join group_tenant_det b on b.tenantmasid = a.tenantmasid
			    where b.grouptenantmasid = '$grouptenantmasid' and a.active='1'
			    union
			    select a.shopmasid,a.tenantmasid,a.leasename,a.buildingmasid from rec_tenant a 
			    inner join group_tenant_det b on b.tenantmasid = a.tenantmasid
			    where b.grouptenantmasid = '$grouptenantmasid' and a.active='1';";
			    
		    $result1 = mysql_query($sql1);
		    if($result1 !=null)
		    {
			$row1 = mysql_fetch_assoc($result1);
			$tenantmasid = $row1['tenantmasid'];
			$shopmasid =$row1['shopmasid'];
			
			$leasename = $row1['leasename'];				
			$buildingmasid=  $row1['buildingmasid'];
			
			$shopoccupied =0;
			$sqlchk ="select leasename,tradingname,shopoccupied from mas_tenant where active='1' and
				  shopmasid='$shopmasid' and shopoccupied='1' and renewalfromid=0
				  union
				  select leasename,tradingname,shopoccupied from rec_tenant where active='1' and
				  shopmasid='$shopmasid' and shopoccupied='1' and renewalfromid=0 ";
			$resultchk = mysql_query($sqlchk);
			if($resultchk !=null)
			{
			    //$rowchk = mysql_fetch_assoc($resultchk);
			    
			    while($rowchk = mysql_fetch_assoc($resultchk))
			    {
				$shopoccupied = $rowchk['shopoccupied'];
				
				$tenant = $rowchk['leasename'];				
				$tradingname = $rowchk['tradingname'];
				if( $tradingname!="")
				$tenant .= "T/A" . $tradingname;				
			    }			   
			    if($shopoccupied <=0 )
			    {
				//activate shop occupied status
				$includedby =$_SESSION['myusername']; 
				$sqlupdate = "update mas_tenant set shopoccupied = '1' ,pin='$pinno',
						includedby='$includedby',includedon='$datetime'
						where tenantmasid=".$tenantmasid ." and active ='1'";
				mysql_query($sqlupdate);
				
				$sqlupdate = "update rec_tenant set shopoccupied = '1' ,pin='$pinno',
						includedby='$includedby',includedon='$datetime'
						where tenantmasid=".$tenantmasid ." and active ='1'";
				mysql_query($sqlupdate);
				
				//remove from waiting list
				$sqlin="Delete from `waiting_list` where grouptenantmasid =$grouptenantmasid";
				$result = mysql_query($sqlin);
				
				// get tenancy code
				$tenancyrefcode = get_tenancyrefcode($leasename,$buildingmasid);
				$createdby =$_SESSION['myusername'];
				$createddatetime =$datetime;
				$sqlcodeinsert = "insert into mas_tenancyrefcode (`tenancyrefcode`,`tenantmasid`,`grouptenantmasid`,`createdby`,`createddatetime`) values
						('$tenancyrefcode',$tenantmasid,$grouptenantmasid,'$createdby','$createddatetime');";
				$resultcode = mysql_query($sqlcodeinsert);				
			    }
			    else
			    {
				    $custom = array('msg'=> "<font color='red'> Alert : Please Discharge $tenant.</font>" ,'s'=>'error');
				    //$custom = array('msg'=> $sqlchk ,'s'=>'error');
				    $response_array[] = $custom;
				    echo '{"error":'.json_encode($response_array).'}';
				    exit;
			    }
			}
		    }
		}
	    }
	    $nk++;
	}
	
        if($result == false)
	{
	    $custom = array('msg'=>mysql_error(),'s'=>"error");
	}
	else
	{	   
	    $sql =" select c.renewalfromid,c.tenantmasid,c.pin,c.leasename,c.tradingname,date_format(c.doc,'%d-%m-%Y') as doc,e.buildingname,d.shopcode,
		    concat (c.poboxno,' - ',c.pincode) as pobox,c.city,c.country,h.cpname,h.cpmobile,h.cplandline,i.shoptype,
		    d.size,f.age as term,g.age as 'rentcycle',j.tenancyrefcode,j.createdby,date_format(j.createddatetime,'%d-%m-%Y') as createddatetime from group_tenant_det a
		    inner join group_tenant_det b on b.grouptenantmasid = a.grouptenantmasid
		    inner join mas_tenant c on c.tenantmasid =  b.tenantmasid
		    inner join mas_shop d on d.shopmasid = c.shopmasid
		    inner join mas_building e on e.buildingmasid = d.buildingmasid
		    inner join mas_age f on f.agemasid = c.agemasidlt
		    inner join mas_age g on g.agemasid = c.agemasidrc
		    inner join mas_tenant_cp h on h.tenantmasid =  c.tenantmasid
		    inner join mas_shoptype i on i.shoptypemasid = c.shoptypemasid
		    inner join mas_tenancyrefcode j on j.grouptenantmasid = a.grouptenantmasid
		    where c.active='1' and  a.grouptenantmasid='$grouptenantmasid' and h.documentname='1'
		    union
		    select c1.renewalfromid,c1.tenantmasid,c1.pin,c1.leasename,c1.tradingname,date_format(c1.doc,'%d-%m-%Y') as doc,e1.buildingname,d1.shopcode,
		    concat (c1.poboxno,' - ',c1.pincode) as pobox,c1.city,c1.country,h1.cpname,h1.cpmobile,h1.cplandline,i1.shoptype,
		    d1.size,f1.age as term,g1.age as 'rentcycle',j1.tenancyrefcode,j1.createdby,date_format(j1.createddatetime,'%d-%m-%Y') as createddatetime from group_tenant_det a1
		    inner join group_tenant_det b1 on b1.grouptenantmasid = a1.grouptenantmasid
		    inner join rec_tenant c1 on c1.tenantmasid =  b1.tenantmasid
		    inner join mas_shop d1 on d1.shopmasid = c1.shopmasid
		    inner join mas_building e1 on e1.buildingmasid = d1.buildingmasid
		    inner join mas_age f1 on f1.agemasid = c1.agemasidlt
		    inner join mas_age g1 on g1.agemasid = c1.agemasidrc
		    inner join rec_tenant_cp h1 on h1.tenantmasid =  c1.tenantmasid
		    inner join mas_shoptype i1 on i1.shoptypemasid = c1.shoptypemasid
		    inner join mas_tenancyrefcode j1 on j1.grouptenantmasid = a1.grouptenantmasid
		    where c1.active='1' and  a1.grouptenantmasid='$grouptenantmasid' and h1.documentname='1'";
	////    $custom = array('msg'=> $sql ,'s'=>'error');
	////			    //$custom = array('msg'=> $sqlchk ,'s'=>'error');
	////			    $response_array[] = $custom;
	////			    echo '{"error":'.json_encode($response_array).'}';
	////			    exit;
	    $result = mysql_query($sql);
	    $renewalfromid=0;
	    $message="";
	    $tenantmasid =0;
	    
	    if($result != null)
	    {
		while($row = mysql_fetch_assoc($result))
		{		    
		    $message .= "<table width='45%' cellspacing='2' cellpadding='2' border='1'>";
		    $message .= "<tr><th align='center' bgcolor='gold'>Headings</th><th>Tenant Details</th></tr>";
		    $message .= "<tr><td align='right' bgcolor='gold'>Building:</td><td>".$row['buildingname']."</td></tr>";
		    $message .= "<tr><td align='right' bgcolor='gold'>Lease Name:</td><td><b>".$row['leasename']."</td></tr>";
		    $message .= "<tr><td align='right' bgcolor='gold'>Trading as:</td><td><b>".$row['tradingname']."</td></tr>";
		    $message .= "<tr><td align='right' bgcolor='gold'>Pin No:</td><td><b>".$row['pin']."</td></tr>";
		    $message .= "<tr><td align='right' bgcolor='gold'>Tenancy Code:</td><td><b>".$row['tenancyrefcode']."</td></tr>";		    
		    $message .= "<tr><td align='right' bgcolor='gold'>Address:</td><td>PO Box: ".rtrim($row['pobox'],"-")."<br>".$row['city']."<br>".$row['country']."</td></tr>";
		    $message .= "<tr><td align='right' bgcolor='gold'>Contact Person:</td><td>".$row['cpname']."<br>".$row['cpmobile']."<br>".$row['cplandline']."</td></tr>";
		    $message .= "<tr><td align='right' bgcolor='silver'>Included by:</td><td>".$row['createdby']." on ".$row['createddatetime']."</td></tr>";
		    $message .= "<tr><td align='right' bgcolor='gold'>Doc:</td><td>".$row['doc']." , Term:".$row['term']."</td></tr>";		    
		    $message .= "<tr><td align='right' bgcolor='gold'>Shop Type:</td><td>".$row['shoptype']."</td></tr>";
		    $message .= "<tr><td align='right' bgcolor='gold'>Shop:</td><td>".$row['shopcode']." , Sqrft: ".$row['size']."</td></tr>";		    
		    $message .= "<tr><td align='right' bgcolor='gold'>Rent Cycle:</td><td>".$row['rentcycle']."</td></tr>";		    
		    //$message .= "</table>";
		    $tenantmasid =$row['tenantmasid'];
		    $renewalfromid = $row['renewalfromid'];		    
		}
		$sqldeposit = "select a.offerlettermasid,b.* from trans_offerletter a 
				inner join trans_offerletter_deposit b on b.offerlettermasid = a.offerlettermasid
				where a.tenantmasid = '$tenantmasid';";
		$resultdeposit = mysql_query($sqldeposit);
		$totalDeposit=0;
		while ($row = mysql_fetch_assoc($resultdeposit))
		{
		    $message .= "<tr><th align='center' bgcolor='#61cff3'>Headings</th><th>Deposit Details</th></tr>";
		    $n = 1;
		    if($row['depositmonthrent'] >0)
		    {
			    $message .="<tr>"					
					    ."<td align='right' bgcolor='#61cff3'>".$row['depositmonthrent']." Months Security Deposit for rent </td>"
					    ."<td align='right'>".number_format($row['rentdeposit'], 0, '.', ',')."</td>"			
					    ."</tr>";$n++;
		    }
		    if($row['depositmonthsc'] > 0)
		    {
			    $message .="<tr>"							
					    ."<td align='right' bgcolor='#61cff3'>".$row['depositmonthsc']." Months security deposit for Service Charge</td>"
					    ."<td align='right'>".number_format($row['scdeposit'], 0, '.', ',')."</td>"
					    ."</tr>";$n++;
		    }
		    if($row['advancemonthrent'] > 0)
		    {
			    $message .="<tr>"							
					    ."<td align='right' bgcolor='#61cff3'>".$row['advancemonthrent']." Month Advance rent with VAT</td>"
					    ."<td align='right'>".number_format($row['rentwithvat'], 0, '.', ',')."</td>"
					    ."</tr>";$n++;
		    }
		    if($row['advancemonthsc'] >0)
		    {
			    $message .="<tr>"							
					    ."<td align='right' bgcolor='#61cff3'>".$row['advancemonthsc']." Month Advance Service Charge with VAT</td>"
					    ."<td align='right'>".number_format($row['scwithvat'], 0, '.', ',')."</td>"
					    ."</tr>";$n++;
		    }
		    if($row['leegalfees'] >0)
		    {
			    $message .="<tr>"							
					    ."<td align='right' bgcolor='#61cff3'>Legal Fees with VAT</td>"
					    ."<td align='right'>".number_format($row['leegalfees'], 0, '.', ',')."</td>"
					    ."</tr>";$n++;
		    }
		    if($row['stampduty'] >0)
		    {
			    $message .="<tr>"							
					    ."<td align='right' bgcolor='#61cff3'>Stamp Duty</td>"
					    ."<td align='right'>".number_format($row['stampduty'], 0, '.', ',')."</td>"
					    ."</tr>";$n++;
		    }
		    if($row['registrationfees'] >0)
		    {
			    $message .="<tr>"							
					    ."<td align='right' bgcolor='#61cff3'>Registration Fees</td>"
					    ."<td align='right'>".number_format($row['registrationfees'], 0, '.', ',')."</td>"
					    ."</tr>";$n++;
		    }
		    if($row['depositTotal'] >0)
		    {
			$message .="<tr>"
				    ."<td align='right' bgcolor='#61cff3'>Total</td>"
				    ."<td align='right'>Kshs.<strong>".number_format($row['depositTotal'], 0, '.', ',')."</strong></td>"
				    ."</tr>";
		    }
		    $totalDeposit +=$row['depositTotal'];
		    $n = 0;		
		}
		//**************************** EMAIL *************************//
		    $subject = "NEW TENANT ADDED INTO PMS";
		    $headmsg= "<b><u>New Tenant details:</u></b><br><br>";
		    if($renewalfromid >0)
		    {
			$subject = "RENEWED TENANT ADDED INTO PMS";
			$headmsg = "<b><u>Renewed Tenant details:</u></b><br><br>";
		    }
		    $address = "prabakaran-accounts@shiloahmega.com";		    
		    $mail->AddAddress($address, "Accounts");
$sql12 = "SELECT email,staff_name FROM `mas_email` WHERE departmentmasid IN('1','9','4','10','5') AND active = '1'";
$result12=mysql_query($sql12);
$recipients = array();
		while($row12 = mysql_fetch_array($result12 ))
    {
   $recipients[$row12['email']] = $row12['staff_name']; 
	}		    
		    // $recipients = array(				   			       
			       // 'marketing@shiloahmega.com' => 'Marketing',
			       // 'arulraj@shiloahmega.com' => 'Arul Raj',
				   // 'dipak@shiloahmega.com' => 'Dipak',
				   // 'mitesh@shiloahmega.com' => 'Mitesh',
			       // 'suresh@shiloahmega.com' => 'Operations',
				   // 'creditcontrol-ho@shiloahmega.com' => 'Credit-Control'
				  
		    // );        
		    foreach($recipients as $email => $name)
		    {
		       $mail->AddCC($email, $name);
		    }
		    $mail->Subject    = $subject;
		    $mail->MsgHTML($headmsg.$message);
		    if(!$mail->Send())
		    {		     
			$custom = array('divContent'=> $mail->ErrorInfo,'s'=>'Success');	   
		    } else
		    {
			$custom = array('divContent'=> "<font color='green'>Tenant Included To Rental Schedule Successfully. Mail sent to ".$address."</font>",'s'=>'Success');		    
		    }	
		//**************************** EMAIL *************************//
	    }	    
	}	
    }
    else if ($action == "exclude")
    {
       foreach ($_GET as $k=>$v) {
	    
	    if ($nk >=1)
	    {
		if ($k == "grouptenantmasid")
		{
		    $grouptenantmasid = $v;
		    
		    // check for shop availability
		    $sqlchk = "select a.shopoccupied,a.tenantmasid from mas_tenant a 
			    inner join group_tenant_det b on b.tenantmasid = a.tenantmasid
			    where b.grouptenantmasid = '$grouptenantmasid' and a.active='1'
			    union
			    select a.shopoccupied,a.tenantmasid from rec_tenant a 
			    inner join group_tenant_det b on b.tenantmasid = a.tenantmasid
			    where b.grouptenantmasid = '$grouptenantmasid' and a.active='1';";
		    $resultchk = mysql_query($sqlchk);
		    if($resultchk !=null)
		    {
			$rowchk = mysql_fetch_assoc($resultchk);			
			$tenantmasid = $rowchk['tenantmasid'];
			
			//de-activate shop occupied status
			$sqlupdate = "update mas_tenant set shopoccupied = '0' where tenantmasid=".$tenantmasid ." and active ='1'";
			mysql_query($sqlupdate);
			$sqlupdate = "update rec_tenant set shopoccupied = '0' where tenantmasid=".$tenantmasid ." and active ='1'";
			mysql_query($sqlupdate);
			
			$sqlex="insert into waiting_list (grouptenantmasid) values ('$grouptenantmasid');";
			$result = mysql_query($sqlex);		    			
		    }		    
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
	    $custom = array('msg'=>"Tenant Excluded From Rental Schedule Successfully.",'s'=>"Success");    
	}
	////$custom = array('msg'=> $sqlex ,'s'=>'error');
    }
    else if ($action == "changedoc")
    {
	$premisedetails  = $_GET['premisedetails'];
	$address="";
	
	$message= $premisedetails ."<br>";
	foreach ($_GET as $k=>$v) {
	    
	    if ($nk >=1)
	    {
		if ($k == "email1")
		{
		    $address=$v;		    
		}
		else  if ($k == "remarks1")
		{
		    $message .=$v;		    
		}
	    }
	    $nk++;
	}		
	//**************************** EMAIL *************************//
	    $subject = "CHANGE DOC - FROM OPERATIONS MANAGER";	    
	    $mail->AddAddress($address, "Receipent");	
$sql12 = "SELECT email,staff_name FROM `mas_email` WHERE departmentmasid IN('1','9','4','5') AND active = '1'";
$result12=mysql_query($sql12);
$recipients = array();
		while($row12 = mysql_fetch_array($result12 ))
    {
   $recipients[$row12['email']] = $row12['staff_name']; 
	}	    
	    // $recipients = array(				   
		           // 'marketing@shiloahmega.com' => 'Marketing',
			       // 'arulraj@shiloahmega.com' => 'Arul Raj',
			       // 'dipak@shiloahmega.com' => 'Dipak',
				   // 'mitesh@shiloahmega.com' => 'Mitesh',
				   // 'creditcontrol-ho@shiloahmega.com' => 'Credit-Control'
				 
	    // );        
	    foreach($recipients as $email => $name)
	    {
	       $mail->AddCC($email, $name);
	    }
	    $mail->Subject    = $subject;
	    $mail->MsgHTML($message);
	    if(!$mail->Send())
	    {		     
		$custom = array('divContent'=> $mail->ErrorInfo,'s'=>'Success');	   
	    } else
	    {
		$custom = array('divContent'=> "<font color='green'> Change of DOC mail sent to ".$address."</font>",'s'=>'Success');		    
	    }	
	//**************************** EMAIL *************************//
    }
    else if ($action == "raiseadvanceinvoice")
    {
	$premisedetails  = $_GET['premisedetails'];
	$address="";	
	$message= $premisedetails ."<br>";
	foreach ($_GET as $k=>$v) {
	    
	    if ($nk >=1)
	    {
		if ($k == "email2")
		{
		    $address=$v;		    
		}
		else  if ($k == "remarks2")
		{
		    $message .=$v;		    
		}
	    }
	    $nk++;
	}	
	//**************************** EMAIL *************************//
	    $subject = "RAISE ADVANCE INVOICE - FROM OPERATIONS MANAGER";   
	    $mail->AddAddress($address, "Receipent");
$sql12 = "SELECT email,staff_name FROM `mas_email` WHERE departmentmasid IN('1','9','4','5','2') AND active = '1'";
$result12=mysql_query($sql12);
$recipients = array();
		while($row12 = mysql_fetch_array($result12 ))
    {
   $recipients[$row12['email']] = $row12['staff_name']; 
	}		
	    // $recipients = array(				   
		           // 'marketing@shiloahmega.com' => 'Marketing',
			       // 'arulraj@shiloahmega.com' => 'Arul Raj',
			       // 'dipak@shiloahmega.com' => 'Dipak',
				   // 'mitesh@shiloahmega.com' => 'Mitesh',
				   // 'ronald-accounts@shiloahmega.com' => 'Ronald',
				   // 'creditcontrol-ho@shiloahmega.com' => 'Credit-Control'
	    // );       
	    foreach($recipients as $email => $name)
	    {
	       $mail->AddCC($email, $name);
	    }
	    $mail->Subject    = $subject;
	    $mail->MsgHTML($message);	    
	    if(!$mail->Send())
	    {		     
		$custom = array('divContent'=> $mail->ErrorInfo,'s'=>'Success');	   
	    } else
	    {
		$custom = array('divContent'=> "<font color='green'> RAISE ADVANCE INVOICE mail sent to ".$address."</font>",'s'=>'Success');		    
	    }	
	//**************************** EMAIL *************************//
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