<?php
		//session_start();
//		if (! isset($_SESSION['myusername']) ){
//			header("location:index.php");
//		}
		include('config.php');
//		include('MasterRef_Folder.php');
                //$companymasid=$_GET['companymasid'];


    $period =  date("d-m-Y",strtotime("-1 day"));
    
    //$foo=false;

//print_r($_POST[0]);

   
  //print_r($_POST);

   
    $subject = "Sales Report - $period";        
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";    
    $headers .= "From:MEGA PMS ERP" . "\r\n";
    //////$headers .= 'Cc: dipak@shiloahmega.com,mitesh@shiloahmega.com,arulraj@shiloahmega.com,juma@shiloahmega.com'. "\r\n";
    $message = '<html><head><style type="text/css"></style></head><body><table>';
    foreach ($_POST as $list){
    $message .=$list;
	
    }
    
    $message .= '</table></body></html>';
  //  echo $message;
    $address = "arulraj@shiloahmega.com";
	//$address = "jacobshavia@gmail.com";
    $mail->AddAddress($address, "Arulraj");	
   //  $address = "jacobshavia@gmail.com";
    // $mail->AddAddress($address, "Jacob");	
     $mail->IsHTML(true)	;
	 		$sql12 = "SELECT email,staff_name FROM `mas_email` WHERE departmentmasid IN('5','9','4') AND active = '1'";
$result12=mysql_query($sql12);
$recipients = array();
		while($row12 = mysql_fetch_array($result12 ))
    {
   $recipients[$row12['email']] = $row12['staff_name']; 
	}
    // $recipients = array(	
	// /* 'jacobshavia@gmail.com' => 'CTO', */
	 // 'prabakaran-accounts@shiloahmega.com' => 'Prabakaran',
	 // 'arulraj@shiloahmega.com' => 'ArulRaj',
	 // 'dipak@shiloahmega.com' => 'Dipak',
	 // 'mitesh@shiloahmega.com' => 'Mitesh',
 // /*         'michael-accounts@shiloahmega.com'=> 'Michael',
         // 'ronald-finance@shiloahmega.com'=> 'Ronald'   */
    // );        
    foreach($recipients as $email => $name)
    {
       $mail->AddCC($email, $name);
    }

    if(is_array($_POST))
    {        
        $mail->Subject    = $subject;
       // $msg=<script> $('.tblmail').html();</script>
	   //$mail->Body=html_entity_decode($message);
       $mail->MsgHTML($message);
        if(!$mail->Send())
        {		     		
           echo($mail->ErrorInfo);
        } else
        {
            echo "mail sent";
        }
       // echo $tablemain;
        //echo $message;
    }
    else
    {
        echo "</br><center><h3>No Sales Found for the period - $period";
   }


?>
