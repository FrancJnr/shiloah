<?php
include('../config.php');
session_start();
//$sqlArray="";
//$cnt =1;
//	foreach ($_GET as $k=>$v) {
//	    $k = preg_replace('/[^a-z]/i', '', $k); 
//	    $sqlArray.= $cnt."--> KEY: ".$k."; VALUE: ".$v."<BR>";
//	    $cnt++;
//	}
//$custom = array('msg'=> $sqlArray ,'s'=>'Success');
//	$response_array[] = $custom;
//	echo '{"error":'.json_encode($response_array).'}';
//	exit;
try{

$companymasid = $_SESSION['mycompanymasid'];
$action = $_GET['action'];
$licensename = $_GET['licensename'];
$buildingmasid = $_GET['buildingmasid'];
$str = strtoupper(substr($licensename,0,2));
$sqlAutoNo = "SELECT licensename FROM mas_daily_license WHERE licensename LIKE '$str%' ORDER BY licensename DESC LIMIT 1";

$result=mysql_query($sqlAutoNo);
if($result != null)
{

    $row = mysql_fetch_array($result);
    $length = strlen($row['licensename']);
    $cstr =  (int)substr($row['licensename'],2,$length) + 1;
    $k = (int)strlen($cstr);
    if($k<=3)
    {
        $k =(int)4; // length of code starts from 5 digist after string E.g. GV00001
    }
    $codeno =str_pad($cstr,$k,"0",STR_PAD_LEFT);
    $licensecode=trim($str).$codeno;
    
    $sqlbuildingshortname = "SELECT shortname FROM mas_building WHERE buildingmasid = $buildingmasid";
    $res = mysql_query($sqlbuildingshortname);
    if($res != null)
    {
	$row = mysql_fetch_array($res);
	$buildingshortname = strtoupper($row['shortname']);
    }
    $licensecode .="-".$buildingshortname;
}


//$custom = array('msg'=> $licensecode ,'s'=>'Success');
//$response_array[] = $custom;
//	echo '{"error":'.json_encode($response_array).'}';
//	exit;

if($action == "Save"){
    $createdby = $_SESSION['myusername'];
        $i=0;
        $key ="";
        foreach($_GET as $key=>$val) {
            if($i > 2)
            {
                if($key == "fromdt")
                {    
                    $cols[] = "".$key."";
                    $vals[] = "'".date('Y-m-d', strtotime($val))."'";
                    //break 1;
                }
                else if($key == "todt")
                {    
                    $cols[] = "".$key."";
                    $vals[] = "'".date('Y-m-d', strtotime($val))."'";
                    //break 1;
                }
                else
                {
			$cols[] = "".$key."";
			$vals[] = "'".$val."'";
                }
		if($i == 3)
		    {
		        $cols[] = "licensecode";
		        $vals[] = "'".$licensecode."'";
		    }
            }
            $i++;
        }
	
	    $cols[] = "companymasid";
        $vals[] = $_SESSION['mycompanymasid'];
		    
        $sql = 'INSERT INTO mas_daily_license ('.implode($cols, ',').') VALUES('.implode($vals, ',').')';
        $m ="Data Saved Successfully";
    
	
}else if($action == "Update"){
    
    $modifiedby = $_SESSION['myusername'];
    $licensemasid=$_GET['licensemasid'];
    $where ="licensemasid ='$licensemasid'";
    $j=0;
    foreach($_GET as $c => $v)
    {
	    if($j > 2)
	    {
		 if($c == "fromdt")
                    {                            
                        $v = date('Y-m-d', strtotime($v));
                        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    }
                    else if($c == "todt")
                    {                            
                        $v = date('Y-m-d', strtotime($v));
                        $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
                    }
		    else
		    {
			$args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
		    }
	    }
	$j++;
    }    
    $c ="companymasid";
    $v = $_SESSION['mycompanymasid'];
    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
    
    $c ="modifiedby";
    $v = $_SESSION['myusername'];
    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
    
    $c ="modifieddatetime";
    $v = $datetime;
    $args[] = '`'.$c.'` = '.(is_int($v) ? (int)$v : "'".mysql_real_escape_string($v)."'");
    
    $sql = 'UPDATE mas_daily_license SET '.implode($args, ',').' WHERE '.$where;
    $m="Data Updated Successfully";
    //$custom = array('msg'=> $sql ,'s'=>'Success');
    //$response_array[] = $custom;
    //echo '{"error":'.json_encode($response_array).'}';
    //exit;
}
    $to = "marketing@shiloahmega.com";
    $address = "marketing@shiloahmega.com";
	$sql12 = "SELECT email,staff_name FROM `mas_email` WHERE departmentmasid IN('5','9') AND active = '1'";
$result12=mysql_query($sql12);
$recipients = array();
		while($row12 = mysql_fetch_array($result12 ))
    {
   $recipients[$row12['email']] = $row12['staff_name']; 
	}
	// $recipients = array(    
		// 'prabakaran-accounts@shiloahmega.com' => 'Prabakaran',
		// 'arulraj@shiloahmega.com' => 'Arul Raj',
		// 'creditcontrol-ho@shiloahmega.com' => 'Credit Ho',
            	    
    // );  
	
	$message="";
    $tr .="</table><br>";
    
    $subject = "Daily Occupation Licence";        
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";    
    $headers .= 'From: MEGA ERP PMS ' . "\r\n";
    $headers .= $cc;
    
    $message .= '<html><body>';             
    $message .= $tr;
    $message .= '</body></html>';        
   $result = mysql_query($sql);
 // $result = mysql_query($sql);
    if($result == false)
    {
       $custom = array('msg'=>mysql_error(),'s'=>mysql_error().$sql);
    }
    else
    {
       $custom = array('msg'=>$m,'s'=>'Success');
        
        //**************************** EMAIL *************************//               
            $mail->AddAddress($address, "Marketing");            
            foreach($recipients as $email => $name)
            {
               $mail->AddCC($email, $name);
            }
            $mail->Subject    = "Daily Occupation Licence";
            $mail->MsgHTML($message);
            
            if(!$mail->Send()) {
              echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
              //echo "Message sent!";
            }        
        //**************************** EMAIL *************************//

        
    }

//}
/* if($result == false)
{
    $custom = array('msg'=>mysql_error(),'s'=>mysql_error().$sql);
}
else
{
    $custom = array('msg'=>$m,'s'=>'Success');
} */

}//try
catch(Exception $err)
{
        $custom = array('msg'=> "Error ".$err->getMessage()." @ Line".$err.getLine() ,'s'=>'Success');
}

$response_array[] = $custom;
echo '{"error":'.json_encode($response_array).'}';
?>