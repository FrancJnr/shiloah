<?php
    $message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html><head><title></title><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <style type="text/css"></style></head><body>';
    $message .= "hello world";
    $message .= '</body></html>';
    
    ini_set('SMTP','192.168.0.1');// DEFINE SMTP MAIL SERVER
    require_once('../PHPMailer/class.phpmailer.php');
    $mail = new PHPMailer(); // defaults to using php "mail()"
    $mail->SetFrom('info@shiloahmega.com', 'PMS Admin');
    $mail->AddReplyTo('info@shiloahmega.com', 'PMS Admin');
    $address = "jacobshavia@gmail.com";    
    $mail->AddAddress($address, "Jacob");   
    $mail->Subject    = "Test Mail";
    $mail->MsgHTML($message);    
    
    if(!$mail->Send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
      echo "Message sent!";
    }

?>