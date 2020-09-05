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
$table = "mas_enquiry";
$buildingmasid = $_GET['buildingmasid'];
$enquirymasid = $_GET['hidenquirymasid'];
$sql = "select buildingname from mas_building where buildingmasid = '$buildingmasid';";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);
$buildingname = $row['buildingname'];
$sql="0";
if($action == "Save")
{            
	    $createdby = $_SESSION['myusername'];
            $i=0;
            $key ="";
            foreach($_GET as $key=>$val) {
                if($i > 1)
                {
			
			if($key == "enquiryreceivedon"){    
				    $cols[] = "enquiryreceivedon";
				    $vals[] = "'".date('Y-m-d', strtotime($val))."'";		
			
                                 //  echo "Here 1" ;
                        }
			else
			{
				    if($key > 0)
				    {
					continue;	
				    }
				    $search_array = array(
							    'flpdt1' => 1,'flpremarks' => 2, 'flpdt2' => 3,'flpstatus' => 4, 'active' => 5,
							    'hidenquirymasid' => 6,'hidenquirydetmasid' =>7
						         );
				    if (array_key_exists($key,$search_array)){
						continue;							
				    }
				    else
				    {
						$cols[] = "".$key."";
						$vals[] = "'".$val."'";
				    
                                      //           echo "here 2";
                                                
                                    }
			}
		     
                }
                $i++;
            }
            if(!isset($_GET['active'])) //if not checked it wont appear in the $_GET array
	    {
	        ////$cols[] = "active";
	        ////$vals[] = "0";
	    }
	    else
	    {
		$cols[] = "active";
	        $vals[] = "1";
	    }
           
	    if(!isset($_GET['active'])) //if not checked it wont appear in the $_GET array
	    {
	        $cols[] = "active";
	        $vals[] = "0";
	    }
	    $cols[] = "companymasid";
            $vals[] = "'".$companymasid."'";
            $cols[] = "createdby";
            $vals[] = "'".$createdby."'";
            $cols[] = "createddatetime";
            $vals[] = "'".$datetime."'";
            
            $sql = 'INSERT INTO `'.$table.'`('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
            
	    ////$custom = array('msg'=>$sql,'s');
	    ////$response_array[] = $custom;
	    ////echo '{"error":'.json_encode($response_array).'}';
	    ////exit;
	    
            $m="Data Saved Successfully";


}else if($action == "Update")
{
	$modifiedby = $_SESSION['myusername'];
        $where =" enquirymasid ='$enquirymasid'";
        $j=0;
        foreach($_GET as $c => $v)
        {
                if($j > 1)
                {
			if($c == "doo")
			{    
			    $c ="doo";
			    $v = date('Y-m-d', strtotime($v));
			    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
			}
			else
			{
				   if($c > 0)
				    {
					continue;	
				    }
				    $search_array = array(
							    'flpdt1' => 1,'flpremarks' => 2, 'flpdt2' => 3,'flpstatus' => 4, 'active' => 5,
							    'hidenquirymasid' => 6,'hidenquirydetmasid' =>7
						         );
				    if (array_key_exists($c,$search_array)){
						continue;							
				    }
				    else
				    {
						$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
				    }				    
			}
                }
            $j++;
        }
         if(!isset($_GET['active'])) //if not checked it wont appear in the $_GET array
	{
	    $c = "active";
	    $v = "0";
	    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
	}
	else
	{
	    $c = "active";
	    $v = "1";
	    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
	}        
	
        $c ="modifiedby";
        $v = $_SESSION['myusername'];
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
        
        $c ="modifieddatetime";
        $v = $datetime;
        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
        
        $sql = 'UPDATE `'.$table.'` SET '.implode($args, ',').' WHERE '.$where;
        
	    ////$custom = array('msg'=> $sql,'s');
	    ////$response_array[] = $custom;
	    ////echo '{"error":'.json_encode($response_array).'}';
	    ////exit;
	    
	$m="Data Updated Successfully";

}
$result = mysql_query($sql);
//$result=true;
if($result == false)
{
    $custom = array('msg'=>mysql_error(),'s'=>$sql);
}
else
{    
//    //$to = "muthukumar@shiloahmega.com";
//    $to = "jacobshavia@gmail.com";
//    ////$to = "juma@shiloahmega.com";
//    $subject = "Tenant Enquiry";    
//    // Always set content-type when sending HTML email
//    $headers = "MIME-Version: 1.0" . "\r\n";
//    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
//    // More headers
//    $headers .= 'From: <info@shiloahmega.com>' . "\r\n";
//    //$headers .= 'Cc: dipak@shiloahmega.com,mitesh@shiloahmega.com,juma@shiloahmega.com,suku@shiloahmega.com' . "\r\n";
//    $message = '<p>Dear All, this Email generated by PMS, upon filling ENQUIRY FORM mail will be automatically generated.</p>';
//    $message = '<p><strong><u>ENQUIRY FORM</u></strong></p>';
//    $message .= '<p><strong>Enquiry form  '.$action.'d by :'.strtoupper($_SESSION['myusername']).'</strong></p>';
//    $message .= '<html><body>';
////$message .= '<img src="http://css-tricks.com/examples/WebsiteChangeRequestForm/images/wcrf-header.png" alt="Website Change Request" />';
//$message .= '<table rules="all" style="border-color: #666;" cellpadding="10" width="50%">';
//$j=0;
//foreach($_GET as $c => $v)
//{
//    if($j > 1)
//    {
//	    if($c > 0)
//	    {
//		continue;	
//	    }
//	    if(($c != "flpdt1") and  ($c != "flpremarks") and ($c != "flpdt2") and ($c != "flpstatus") and ($c != "hidenquirydetmasid") and ($c != "hidenquirymasid") and ($c != "active") ){
//		if($c == "buildingmasid")
//		$message .= "<tr style='background: #eee;'><td><strong>Building:</strong> </td><td>".strtoupper($buildingname)."</td></tr>";
//		else if($c == "compname")
//		$message .= "<tr style='background: #eee;'><td><strong>Company</strong> </td><td>".strtoupper($v)."</td></tr>";
//		else if($c == "comptype")
//		$message .= "<tr style='background: #eee;'><td><strong>Organization Type</strong> </td><td>".strtoupper($v)."</td></tr>";
//		else if($c == "nob")
//		$message .= "<tr style='background: #eee;'><td><strong>Nature of Business</strong> </td><td>".strtoupper($v)."</td></tr>";
//		else if($c == "dirname")
//		$message .= "<tr style='background: #eee;'><td><strong>Director</strong> </td><td>".strtoupper($v)."</td></tr>";
//		else if($c == "cpname")
//		$message .= "<tr style='background: #eee;'><td><strong>Contact Person</strong> </td><td>".strtoupper($v)."</td></tr>";
//		else if($c == "area")
//		$message .= "<tr style='background: #eee;'><td><strong>Approx Sqrft Required</strong> </td><td>".strtoupper($v)."</td></tr>";
//		else
//		$message .= "<tr style='background: #eee;'><td><strong>".strtoupper($c).":</strong> </td><td>".strtoupper($v)."</td></tr>";
//		if(strtoupper($c) == "EMAILID")
//		{
//		    $custemail = $v;
//		}
//	    }
//    }
//    $j++;
//}
//$message .= "</table>";
//$message .= "</body></html>";
//    //ini_set('SMTP','192.168.0.1');// DEFINE SMTP MAIL SERVER
//    //mail($to,$subject,$message,$headers);
//    
//    
//     //**************************** EMAIL *************************//
//        //ini_set('SMTP','mail.busgateway.is.co.za');// DEFINE SMTP MAIL SERVER
//        require_once('../PHPMailer/class.phpmailer.php');
//        $mail = new PHPMailer(); // defaults to using php "mail()"
//        
//        $mail->CharSet = "UTF-8"; 
//        $mail->IsSMTP(); // send via SMTP 
//        $mail->Host = "mail.busgateway.is.co.za"; // SMTP servers 
//        $mail->SMTPAuth = true; // turn on SMTP authentication 
//        $mail->Username = "info@shiloahmega.com"; // SMTP username 
//        $mail->Password = "MegaProps@2501"; // SMTP password 
//        $mail->From = "info@shiloahmega.com"; 
//        $mail->FromName = "MEGA PMS ERP";
//        $mail->IsHTML(true);
//        
//        //$mail->SetFrom('info@shiloahmega.com', 'PMS Admin');
//        //$mail->AddReplyTo('info@shiloahmega.com', 'PMS Admin');
//        
//        //$address = "juma@shiloahmega.com";
//        //$mail->AddAddress($address, "Prabhu");
//	
//        $address = "techsavannaltd@gmail.com";   
//      //  $address = 'marketing@shiloahmega.com';
//        $mail->AddAddress($address, "Marketing");
//        $recipients = array(
//            'jacobshavia@gmail.com' => 'Marketing',
//	   //'marketing@shiloahmega.com' => 'Marketing',
//           //'dipak@shiloahmega.com' => 'Dipak',
//	   //'mitesh@shiloahmega.com' => 'Mitesh',
//          // 'juma@shiloahmega.com' => 'Prabhu',
//	  // 'marketing-ho@shiloahmega.com' => 'Marketing Ho'
//        ); 
//        foreach($recipients as $email => $name)
//        {
//           $mail->AddCC($email, $name);
//        }
//        $mail->Subject    = "Tenant Enquiry";
//        $mail->MsgHTML($message);
//        
//        if(!$mail->Send()) {
//          echo "Mailer Error: " . $mail->ErrorInfo;
//        } else {
//          echo "Message sent!";
//        }
//        
//    //**************************** EMAIL *************************//
//
//    if($action != "Update")
//    {
//	    if($custemail !="")
//	    {
//			// thanks email to customers START--------------------------------------------
//			$headers = "MIME-Version: 1.0" . "\r\n";
//			$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
//			// More headers
//			$headers .= 'From: <info@shiloahmega.com>' . "\r\n";
//			$subject ="Thanks for space enquiry - ".strtoupper($buildingname);		    
//			$message  ="Dear Sir/Madam,</br>";
//			$message .="We thank you for your visit today to our shopping Mall ".strtoupper($buildingname).".
//				    </br> We will get back to you soon.";
//			$message .="</br>Regards</br>
//				    </br>Marketing Manager</br>Shiloah Investments Ltd
//				    </br>Mega Plaza, 3rd Floor, Block 'A'
//				    </br>PO Box 2501, KISUMU
//				    </br>Phone : 057 - 2021333 / 2023550 / 2021269
//				    </br>Fax : 057- 2021658
//				    </br>Mobile : + 254 726 522 720
//				    </br>Email  : marketing@shiloahmega.com;" ;
//				
//			$address = $custemail;   
//			$mail->AddAddress($address, "");			
//			$mail->Subject    = $subject;
//			$mail->MsgHTML($message);
//	    
//			if(!$mail->Send()) {
//			   echo "Mailer Error: " . $mail->ErrorInfo;
//			} else {
//			  echo "Message sent!";
//			}
//		    //mail($custemail,$subject,$message,$headers);
//		    // END --------------------------------------------
//		    
//	    }
//    }
    
    $custom = array('msg'=>$m,'s'=>"Success");
    
}


$response_array[] = $custom;
echo '{"error":'.json_encode($response_array).'}';
exit;

}
catch (Exception $err)
{
    $custom = array('msg'=>"Error: ".$err->getMessage().", Line No:".$err->getLine()
                    ,'s'=>"Error");    
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
?>