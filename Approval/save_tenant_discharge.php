<?php
include('../config.php');
$response_array = array();
session_start();
$action = $_GET['action'];
$companymasid = $_SESSION['mycompanymasid'];
$user =  strtolower($_SESSION['myusername']);
include('approvers.php');
    $grouptenantmasid = $_GET['hidgrouptenantmasid'];
    $sql="";
    $leasename = $_GET['hidLeasename'];
    //$sqlArray="";
    //$cnt =1;
    //	foreach ($_GET as $k=>$v) {
    //	    $k = preg_replace('/[^a-z]/i', '', $k); 
    //	    $sqlArray.= $cnt."--> KEY: ".$k."; VALUE: ".$v."<BR>";
    //	    $cnt++;
    //	}
    //$custom = array('msg'=> $sqlArray ,'s'=>'error');
    //	$response_array[] = $custom;
    //	echo '{"error":'.json_encode($response_array).'}';
    //	exit;
    $tenancyrefcode="";
    $sql="select tenancyrefcode from mas_tenancyrefcode where grouptenantmasid ='$grouptenantmasid'";
    $result=mysql_query($sql);
    if($result !=null)
    {
	$row = mysql_fetch_assoc($result);
	$tenancyrefcode=$row["tenancyrefcode"];
    }
    $sql1 = "select a.tenantmasid,b.tradingname,c.buildingname,d.shopcode,d.size from group_tenant_det a  
		 inner join mas_tenant b on b.tenantmasid = a.tenantmasid
                 inner join mas_building c on c.buildingmasid = b.buildingmasid
                 inner join mas_shop d on d.shopmasid = b.shopmasid
		 where a.grouptenantmasid = $grouptenantmasid  and b.active='1';";
		 
    $result1=mysql_query($sql1);
    if($result1 !=null)
    {
        $rcount = mysql_num_rows($result1);
        if($rcount ==0) 
        {
            $sql1 = "select a.tenantmasid,b.tradingname,c.buildingname,d.shopcode,d.size from group_tenant_det a  
                        inner join rec_tenant b on b.tenantmasid = a.tenantmasid
                        inner join mas_building c on c.buildingmasid = b.buildingmasid
                        inner join mas_shop d on d.shopmasid = b.shopmasid
                        where a.grouptenantmasid = $grouptenantmasid  and b.active='1'";
        }
    }	    
    $result1 = mysql_query($sql1);
    $cnt = mysql_num_rows($result1);
    $tradingname =$leasename;
    $shop="";    
    while($row1 = mysql_fetch_assoc($result1))
    {
        if($row1['tradingname'] !="")
        {
            $tradingname .=" <b>T/A</b> ";
            $tradingname .= $row1['tradingname'];
        }
        $shop = "<b>".$row1['buildingname']."</b><br>".$row1['shopcode']."<br>Sqrft: ".$row1['size'];
    }
    
$to="";$cc="";
try{
    
    $where ="grouptenantmasid ='$grouptenantmasid'";
    
    $tr = "<table width='50%' cellspacing='2' cellpadding='2' border='1'>";
    $tr .= "<tr><th colspan ='2' align='center' bgcolor='#fac78d'>Tenant Discharge Details updated by Mr.".strtoupper($user)."</th></tr>";
    $tr .= "<tr><th align='center' bgcolor='#95d9f2' width='30%'>Headings</th><th bgcolor='#95d9f2' width='70%'>Tenant Details</th></tr>";
    $tr  .="<tr><td align='right' bgcolor='#dbdbb7'> Tenant:</td><td align='left'>$tradingname</td>";
    $tr  .="<tr><td align='right' bgcolor='#dbdbb7'> Shop:</td><td align='left'>$shop</td>";
    $tr  .="<tr><td align='right' bgcolor='#dbdbb7'> Tenancy Code:</td><td align='left'>$tenancyrefcode</td>";
    
    if (array_key_exists($user, $search_array_op))    
    {        
       
        $table ="trans_tenant_discharge_op";
        $j=0;                
        
        foreach($_GET as $c => $v)
        {
            if($j > 2)
            {
                if($c == "nrodt")
                {    
                    $c ="nrodt";                    
                    $v = date('Y-m-d', strtotime($v));
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                }
                else if($c == "vacatingdt")
                {    
                    $c ="vacatingdt";                    
                    $v = date('Y-m-d', strtotime($v));
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                }
                else if($c == "opapproval")
                {    
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                   
                    if ($v == '1')
                        $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Status:</td><td align='left'>Vacant</td>";
                    else
                        $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Status:</td><td align='left'>Occupied</td>";
                    break;
                }
                else
                {
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    if($c == "oplegal")
                    {
                        if($v==0)
                            $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Legal:</td><td align='left'>No</td>";
                        else
                            $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Legal:</td><td align='left'>Yes</td>";
                    }
                }
            }
            $j++;
        };
        
        $c ="modifiedby";
        $v = $_SESSION['myusername'];
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
        
        $c ="modifieddatetime";
        $v = $datetime;
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");        
        
        $sql = 'UPDATE `'.$table.'` SET '.implode($args, ',').' WHERE '.$where;
        
        $m ="Data Updated Successfully";
//        $to = "prabakaran-accounts@shiloahmega.com";
//        $cc = 'Cc: marketing@shiloahmega.com,arulraj@shiloahmega.com,juma@shiloahmega.com,suresh@shiloahmega.com' . "\r\n";
        
        $address = "prabakaran-accounts@shiloahmega.com"; 
$sql12 = "SELECT email,staff_name FROM `mas_email` WHERE departmentmasid IN('1','4','5','8','9') AND active = '1'";
$result12=mysql_query($sql12);
$recipients = array();
		while($row12 = mysql_fetch_array($result12))
    {
   $recipients[$row12['email']] = $row12['staff_name']; 
	}
        // $recipients = array(                      
	    // 'marketing@shiloahmega.com' => 'Marketing',
        // 'arulraj@shiloahmega.com' => 'Arul Raj',//admin
		// 'dipak@shiloahmega.com' => 'Dipak', //Director
		// 'mitesh@shiloahmega.com' => 'Mitesh',//Director
		// 'prabakaran-accounts@shiloahmega.com' => 'Prabakaran', //Credit Control
		// 'creditcontrol-ho@shiloahmega.com' => '', //Credit Control
	    // 'suresh@shiloahmega.com' => 'Suresh' //Operations
        // );    

    }        
    else if (array_key_exists($user, $search_array_ac))   
    {
    
    $table ="trans_tenant_discharge_ac";
    $j=0;
    foreach($_GET as $c => $v)
    {
        if($j > 2)
        {
            if($c == "chqdate")
            {    
                $c ="chqdate";                    
                $v = date('Y-m-d', strtotime($v));
                $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
            }                
            else if($c == "acapproval")
            {    
                $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                
                 if ($v == '1')
                    $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Status:</td><td align='left'>Vacant</td>";
                else
                    $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Status:</td><td align='left'>Occupied</td>";             
                break;
            }
            else
            {
                $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");                    
                
                if($c == "aclegal")
                {
                    if($v==0)
                        $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Legal:</td><td align='left'>No</td>";
                    else
                        $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Legal:</td><td align='left'>Yes</td>";
                }
                else if($c == "outstandingpayment")
                {                        
                    $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Balance:</td><td align='left'>$v</td>";
                }
            }
        }
        $j++;
    };
        
    $c ="modifiedby";
    $v = $_SESSION['myusername'];
    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
    
    $c ="modifieddatetime";
    $v = $datetime;
    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
            
    $sql = 'UPDATE `'.$table.'` SET '.implode($args, ',').' WHERE '.$where;
    
    $m ="Data Updated Successfully";
    
    $to = "marketing@shiloahmega.com";
    //$to="jacobshavia@gmail.com";
//    $cc = 'Cc: prabakaran-accounts@shiloahmega.com,arulraj@shiloahmega.com,juma@shiloahmega.com,suresh@shiloahmega.com' . "\r\n";
    $address = "marketing@shiloahmega.com";
	$sql12 = "SELECT email,staff_name FROM `mas_email` WHERE departmentmasid IN('1','4','5','8','9') AND active = '1'";
	$result12=mysql_query($sql12);
$recipients = array();
		while($row12 = mysql_fetch_array($result12))
    {
   $recipients[$row12['email']] = $row12['staff_name']; 
	}
	
    // $recipients = array(                       
		 // 'marketing@shiloahmega.com' => 'Marketing',
        // 'arulraj@shiloahmega.com' => 'Arul Raj',
		// 'dipak@shiloahmega.com' => 'Dipak',
		// 'mitesh@shiloahmega.com' => 'Mitesh',
		// 'prabakaran-accounts@shiloahmega.com' => 'Prabakaran',
		// 'creditcontrol-ho@shiloahmega.com' => 'Credit Ho',
	    // 'suresh@shiloahmega.com' => 'Suresh'

	    
    // ); 
  
}else if (array_key_exists($user, $search_array_admin)) {
     $table ="trans_tenant_discharge_op";
        $j=0;                
        
        foreach($_GET as $c => $v)
        {
            if($j > 2)
            {
                if($c == "nrodt")
                {    
                    $c ="nrodt";                    
                    $v = date('Y-m-d', strtotime($v));
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                }
                else if($c == "vacatingdt")
                {    
                    $c ="vacatingdt";                    
                    $v = date('Y-m-d', strtotime($v));
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                }
                else if($c == "opapproval")
                {    
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                   
                    if ($v == '1')
                        $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Status:</td><td align='left'>Vacant</td>";
                    else
                        $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Status:</td><td align='left'>Occupied</td>";
                    break;
                }
                else
                {
                    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    if($c == "oplegal")
                    {
                        if($v==0)
                            $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Legal:</td><td align='left'>No</td>";
                        else
                            $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Legal:</td><td align='left'>Yes</td>";
                    }
                }
            }
            $j++;
        };
        
        $c ="modifiedby";
        $v = $_SESSION['myusername'];
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
        
        $c ="modifieddatetime";
        $v = $datetime;
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");        
        
        $sql = 'UPDATE `'.$table.'` SET '.implode($args, ',').' WHERE '.$where;
        
        //$m ="Data Updated Successfully";
    
    $table ="trans_tenant_discharge_ac";
    $k=0;
    foreach($_GET as $c => $v)
    {
        if($k > 2)
        {
            if($c == "chqdate")
            {    
                $c ="chqdate";                    
                $v = date('Y-m-d', strtotime($v));
                $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
            }                
            else if($c == "acapproval")
            {    
                $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                
                 if ($v == '1')
                    $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Status:</td><td align='left'>Vacant</td>";
                else
                    $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Status:</td><td align='left'>Occupied</td>";             
                break;
            }
            else
            {
                $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");                    
                
                if($c == "aclegal")
                {
                    if($v==0)
                        $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Legal:</td><td align='left'>No</td>";
                    else
                        $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Legal:</td><td align='left'>Yes</td>";
                }
                else if($c == "outstandingpayment")
                {                        
                    $tr .="<tr><td align='right' bgcolor='#dbdbb7'> Balance:</td><td align='left'>$v</td>";
                }
            }
        }
        $k++;
    };
        
    $c ="modifiedby";
    $v = $_SESSION['myusername'];
    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
    
    $c ="modifieddatetime";
    $v = $datetime;
    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
            
    $sql = 'UPDATE `'.$table.'` SET '.implode($args, ',').' WHERE '.$where;
    
    $m ="Data Updated by Admin Successfully";
    $to = "marketing@shiloahmega.com";
    //$to="jacobshavia@gmail.com";
//    $cc = 'Cc: prabakaran-accounts@shiloahmega.com,arulraj@shiloahmega.com,juma@shiloahmega.com,suresh@shiloahmega.com' . "\r\n";
    $address = "marketing@shiloahmega.com";
	/* 
    $recipients = array(                       
		'prabakaran-accounts@shiloahmega.com' => 'Prabakaran',
		'arulraj@shiloahmega.com' => 'Arul Raj',
                'prabakaran-accounts@shiloahmega.com' => 'Prabakaran',	
	        'suresh@shiloahmega.com' => 'Suresh'

	    
    );     */
	$sql12 = "SELECT email,staff_name FROM `mas_email` WHERE departmentmasid IN('5','9') AND active = '1'";
	$result12=mysql_query($sql12);
$recipients = array();
		while($row12 = mysql_fetch_array($result12))
    {
   $recipients[$row12['email']] = $row12['staff_name']; 
	}
	// $recipients = array(                       
		// 'prabakaran-accounts@shiloahmega.com' => 'Prabakaran',
		// 'arulraj@shiloahmega.com' => 'Arul Raj',
		// 'creditcontrol-ho@shiloahmega.com' => 'Credit Ho',
        // 'prabakaran-accounts@shiloahmega.com' => 'Prabakaran'	
	       	    
    // );  
    
}
    
    $message="";
    $tr .="</table><br>";
    
    $subject = "Tenant Discharge";        
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";    
    $headers .= 'From: MEGA ERP PMS ' . "\r\n";
    $headers .= $cc;
    
    $message .= '<html><body>';             
    $message .= $tr;
    $message .= '</body></html>';             
    
    //$custom = array('msg'=> $tr ,'s'=>'error');
    //$response_array[] = $custom;
    //echo '{"error":'.json_encode($response_array).'}';
    //exit;
    
    
    $result = mysql_query($sql);
    if($result == false)
    {
        $custom = array('msg'=>mysql_error(),'s'=>$sql);
    }
    else
    {
        $custom = array('msg'=>$m,'s'=>"Success");
        
        //**************************** EMAIL *************************//               
            $mail->AddAddress($address, "Marketing");            
            foreach($recipients as $email => $name)
            {
               $mail->AddCC($email, $name);
            }
            $mail->Subject    = "Tenant Discharge";
            $mail->MsgHTML($message);
            
            if(!$mail->Send()) {
              echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
              //echo "Message sent!";
            }        
        //**************************** EMAIL *************************//

        
    }
    $upd="";
    $sql1="select d.tenantmasid,d.leasename,a.acapproval,b.opapproval from  trans_tenant_discharge_ac a 
            inner join trans_tenant_discharge_op b on b.grouptenantmasid = a.grouptenantmasid
            inner join group_tenant_det c on c.grouptenantmasid = a.grouptenantmasid
            inner join mas_tenant d on d.tenantmasid = c.tenantmasid
            where a.grouptenantmasid = $grouptenantmasid;";
    $result1=mysql_query($sql1);
    $rcount = mysql_num_rows($result1);
    if($rcount == 0)
    {
        $sql1="select d.tenantmasid,d.leasename,a.acapproval,b.opapproval from  trans_tenant_discharge_ac a 
            inner join trans_tenant_discharge_op b on b.grouptenantmasid = a.grouptenantmasid
            inner join group_tenant_det c on c.grouptenantmasid = a.grouptenantmasid
            inner join rec_tenant d on d.tenantmasid = c.tenantmasid
            where a.grouptenantmasid = $grouptenantmasid;";
    }
    $result1=mysql_query($sql1);
    if($result1 !=null)
    {  
        $rcount = mysql_num_rows($result1);
        if($rcount > 0) 
        {
            while($row1 = mysql_fetch_assoc($result1))
            {
                $tmasid = $row1["tenantmasid"];
                $opapp = $row1["opapproval"];
                $acapp = $row1["acapproval"];
                if(($opapp =='1') or ($acapp =='1')) //edited for either
                {
                    $upd ="update mas_tenant set active = '0' and shopoccupied='0' where tenantmasid = $tmasid;";
                    mysql_query($upd);
                    $upd ="update rec_tenant set active = '0' and shopoccupied='0' where tenantmasid = $tmasid;";
                    mysql_query($upd);
                    $m ="Tenants deactivated successfully";
                }
                else if(($opapp =='0') or ($acapp =='0'))
                {
                    $sql3 =  "select * from rec_tenant where tenantmasid =$tmasid";
                    $result3=mysql_query($sql3);
                    $rcount3 = mysql_num_rows($result3);
                    if($rcount3 == 0)
                    {
                        $upd ="update mas_tenant set active = '1' where tenantmasid = $tmasid;";
                        mysql_query($upd);
                    }
                    else
                    {
                        $upd ="update rec_tenant set active = '1' where tenantmasid = $tmasid;";
                        mysql_query($upd);
                    }
                }
            }
        }
        $custom = array('msg'=> $m ,'s'=>'Success');
        $response_array[] = $custom;
        echo '{"error":'.json_encode($response_array).'}';
        exit;
    }
    
    $response_array[] = $custom;
    echo '{
            "error":'.json_encode($response_array).
        '}';       
} //try
catch (Exception $err)
{
    $custom = array(
                'msg'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),
                's'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}

?>