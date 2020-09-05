<?php
	include('config.php');
    require_once('phpmailer/class.phpmailer.php');
    $mail = new PHPMailer(); // defaults to using php "mail()"
    $mail->CharSet = "UTF-8";
    $mail->IsSMTP(); // send via SMTP
    $mail->Host = "mail.busgateway.is.co.za"; // SMTP servers
    $mail->SMTPAuth = true; // turn on SMTP authentication
    $mail->Username = "info@shiloahmega.com"; // SMTP username
    $mail->Password = "MegaProps@2501"; // SMTP password
    $mail->From = "info@shiloahmega.com";
    $mail->FromName = "MEGA PROPERTIES";
    $mail->IsHTML(true);
            $mail->SMTPDebug = 1;
    $subject = "TEST MAIL FROM MEGA";
    $address = "francis@techsavanna.technology";
	$sql12 = "SELECT email,staff_name FROM `mas_email` WHERE departmentmasid IN('10')";
$result12=mysql_query($sql12);
$recipients = array();
		while($row12 = mysql_fetch_array($result12))
    {
		// $new_array[] = $row12;
   $recipients[$row12['email']] = $row12['staff_name']; 
	}
   $mail->AddAddress($address, "PRABHU");
    //$recipients = array(        
    //    'juma@shiloahmega.com' => 'Prabhu'
    //);
    foreach($recipients as $email => $name)
    {
        $mail->AddCC($email, $name);
    }
    $message="Test Emails";
    $mail->Subject    = $subject;
    $mail->MsgHTML($message);
    $mail->Send();
?>