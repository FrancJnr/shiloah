<?php

////function to validate the email address
////returns false if email is invalid
function checkEmail($email){
   
	if(eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $email)){
		return FALSE;
	}

	list($Username, $Domain) = split("@",$email);

	if(@getmxrr($Domain, $MXHost)){
		return TRUE;
   
	} else {
		if(@fsockopen($Domain, 25, $errno, $errstr, 30)){
			return TRUE; 
		} else {

			return FALSE; 
		}
	}
}	


echo json_encode($_POST['email']);

////response array with status code and message
//$response_array = array();
//
////validate the post form
//
////check the name field
//if(empty($_POST['name'])){
//
//	//set the response
//	$response_array['status'] = 'error';
//	$response_array['message'] = 'Name is blank';
//
////check the email field
//} elseif(!checkEmail($_POST['email'])) {
//
//	//set the response
//	$response_array['status'] = 'error';
//	$response_array['message'] = 'Email is blank or invalid';
//
////check the message field
//} elseif(empty($_POST['message'])) {
//
//	//set the response
//	$response_array['status'] = 'error';
//	$response_array['message'] = 'Message is blank';
//
//
////form validated. send email
//} else {
//	
//	//send the email
//	$body = $_POST['name'] . " sent you a message\n";
//	$body .= "Details:\n\n" . $_POST['message'];
//	mail($_POST['email'], "SUBJECT LINE", $body);
//
//	//set the response
//	$response_array['status'] = 'success';
//	$response_array['message'] = 'Email sent!';
//
//}
//
//
//echo json_encode($response_array);

?>