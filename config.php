<?php

//error_reporting(0);
include('tcpdf/tcpdf.php');
       


//set php to throw all warnings and exceptions
//set_error_handler(create_function('$p1, $p2, $p3, $p4', 'throw new ErrorException($p2, 0, $p1, $p3, $p4);'), E_ALL);
// Connection's Parameters
$db_host="localhost";
$db_name="shiloahmsk";
/*$username="shiloah";
$password="shiloah123"; */
$username="root";
$password="";

$db_con=mysql_connect($db_host,$username,$password);
$connection_string=mysql_select_db($db_name);

// Connection
mysql_connect($db_host,$username,$password);
mysql_select_db($db_name);
date_default_timezone_set('Africa/Nairobi');
$datetime = date('Y-m-d H:i:s'); // mysql date format
function getIP() { 
   $ip; 
   if (getenv("HTTP_CLIENT_IP")) 
   $ip = getenv("HTTP_CLIENT_IP"); 
   else if(getenv("HTTP_X_FORWARDED_FOR")) 
   $ip = getenv("HTTP_X_FORWARDED_FOR"); 
   else if(getenv("REMOTE_ADDR")) 
   $ip = getenv("REMOTE_ADDR"); 
   else 
   $ip = "UNKNOWN";
   return $ip; 
}
function clean($string) {
   $string = str_replace(' ', ' ', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
}
function getinvno($companymasid)
{
    $sql = "select invendno+1 as invno from invoice_no where companymasid =$companymasid";
    $result = mysqli_query($sql);
    $row = mysqli_fetch_assoc($result);
    return $row['invno'];
}

function setinvno($companymasid,$invendno,$dt)
{
    $upd = "update invoice_no set invendno = '$invendno' , invupdatedon= '$dt' where companymasid =$companymasid";
    mysql_query($upd);    
    return "";
}

function setcreditnoteno($companymasid,$invcrendno,$dt)
{
   $upd = "update invoice_no_cr set invcrendno = '$invcrendno' , invcrupdatedon= '$dt' where companymasid =$companymasid";
   mysql_query($upd);    
   return "";
}

       //convert number towords

function convert_number_to_words($number) {

    $hyphen = '-';
    $conjunction = ' and ';
    $separator = ', ';
    $negative = 'negative ';
    $decimal = ' point ';
    $dictionary = array(
        0 => 'Zero',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Fourty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety',
        100 => 'Hundred',
        1000 => 'Thousand',
        1000000 => 'Million',
        1000000000 => 'Billion',
        1000000000000 => 'Trillion',
        1000000000000000 => 'Quadrillion',
        1000000000000000000 => 'Quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ((int) ($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}


function setdebitnoteno($companymasid,$invcrendno,$dt)
{
   $upd = "update invoice_no_dr set invdrendno = '$invcrendno' , invdrupdatedon= '$dt' where companymasid =$companymasid";
   mysql_query($upd);    
   return "";
}
function gettenancyrefcode($grouptenantmasid)
{
   $sql = "select tenancyrefcode from mas_tenancyrefcode where grouptenantmasid =$grouptenantmasid";
   $result = mysql_query($sql);
   $row = mysql_fetch_assoc($result);
   return $row['tenancyrefcode'];
}

function dmy_to_ymd($fromdt)
{
    $fromdt = explode('-',$fromdt);
    $fromdt = $fromdt[2] . '-' . $fromdt[1] . '-' . $fromdt[0];
    $fromdt = date('Y-m-d', strtotime($fromdt));
    return $fromdt;
}


function get_tenancyrefcode($leasename,$buildingmasid)
{   
   $leasename = preg_replace('/\s\s+/', '', clean($leasename)); // remove white space and special chars
   $str = strtoupper(substr($leasename,0,2));
   $sqlAutoNo = "select tenancyrefcode from mas_tenancyrefcode where tenancyrefcode like '".$str."%' order by tenancyrefcode desc limit 1;";
   $result=mysql_query($sqlAutoNo);
   if($result != null)
   {
      $row = mysql_fetch_array($result);
      $length = strlen($row['tenancyrefcode']);
      $cstr =  (int)substr($row['tenancyrefcode'],2,$length) + 1;
      $k = (int)strlen($cstr);
      if($k<=3)
      {
	  $k =(int)3; // length of code starts from 5 digist after string E.g. GV001
      }
      $codeno =str_pad($cstr,$k,"0",STR_PAD_LEFT);
      $tenancyrfcode=trim($str).$codeno;
      
      $sqlbuildingshortname = "select shortname from mas_building where buildingmasid = $buildingmasid";
      $res = mysql_query($sqlbuildingshortname);
      if($res != null)
      {
	 $row = mysql_fetch_array($res);
	 $buildingshortname = strtoupper($row['shortname']);
      }
      $tenancyrfcode .="".$buildingshortname;
   }
   return $tenancyrfcode;
}
/** 
*  Function:   convert_number 
*
*  Description: 
*  Converts a given integer (in range [0..1T-1], inclusive) into 
*  alphabetical format ("one", "two", etc.)
*
*  @int
*
*  @return string
* e.q. convert_number('2012')
*/
function convert_number($number) 
{ 
    if (($number < 0) || ($number > 999999999)) 
    { 
	throw new Exception("Number is out of range");
    } 

    $Gn = floor($number / 1000000);  /* Millions (giga) */ 
    $number -= $Gn * 1000000; 
    $kn = floor($number / 1000);     /* Thousands (kilo) */ 
    $number -= $kn * 1000; 
    $Hn = floor($number / 100);      /* Hundreds (hecto) */ 
    $number -= $Hn * 100; 
    $Dn = floor($number / 10);       /* Tens (deca) */ 
    $n = $number % 10;               /* Ones */ 

    $res = ""; 

    if ($Gn) 
    { 
        $res .= convert_number($Gn) . " Million"; 
    } 

    if ($kn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($kn) . " Thousand"; 
    } 

    if ($Hn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($Hn) . " Hundred"; 
    } 

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
        "Nineteen"); 
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", 
        "Seventy", "Eigthy", "Ninety"); 

    if ($Dn || $n) 
    { 
        if (!empty($res)) 
        { 
            $res .= " and "; 
        } 

        if ($Dn < 2) 
        { 
            $res .= $ones[$Dn * 10 + $n]; 
        } 
        else 
        { 
            $res .= $tens[$Dn]; 

            if ($n) 
            { 
                $res .= "-" . $ones[$n]; 
            } 
        } 
    } 

    if (empty($res)) 
    { 
        $res = "zero"; 
    } 

    return $res; 
}

if(isset($_SESSION['myusermasid']))
{
   $uid = $_SESSION['myusermasid'];
   $page = $_SERVER['REQUEST_URI'];
   $ip = $_SERVER['REMOTE_ADDR'];
   //$sql="REPLACE INTO mas_user_online set usermasid ='$uid', time='$datetime',page='$page', ip='$ip'";
   //$sql = "insert into mas_user_online (usermasid,time,page,ip) values ('$uid','$datetime','$page','$ip')";
   //mysql_query($sql);
}
require_once('PHPMailer/class.phpmailer.php');
        $mail = new PHPMailer(); // defaults to using php "mail()"        
        $mail->CharSet = "UTF-8"; 
        $mail->IsSMTP(); // send via SMTP 
        $mail->Host = "mail.busgateway.is.co.za"; // SMTP servers 
        $mail->SMTPAuth = true; // turn on SMTP authentication 
        $mail->Username = "info@shiloahmega.com"; // SMTP username 
        $mail->Password = "MegaProps@2501"; // SMTP password 
        $mail->From = "info@shiloahmega.com"; 
        $mail->FromName = "MEGA PMS ERP";
        $mail->IsHTML(true);
	
?>
