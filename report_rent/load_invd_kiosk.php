<?php
include('../config.php');
session_start();
////$sqlArray="";
////$cnt =1;
////	foreach ($_GET as $k=>$v) {	    
////	    $sqlArray.= $cnt."--> KEY: ".$k."; VALUE: ".$v."<BR>";
////	    $cnt++;
////	}
////$t1= "<p class ='printable'>hi hello</p>";
////$custom = array('result'=> $t1 ,'s'=>'Success');
////	$response_array[] = $custom;
////	echo '{"error":'.json_encode($response_array).'}';
////	exit;

try
{
    $invoice_date_from = $_GET['invdtfrom'];
    $invdtfrom = explode("-",$_GET['invdtfrom']);
    $invdtfrom = $invdtfrom[2]."-".$invdtfrom[1]."-".$invdtfrom[0];    
    
    $invoice_date_to = $_GET['invdtto'];
    $invdtto = explode("-",$_GET['invdtto']);
    $invdtto = $invdtto[2]."-".$invdtto[1]."-".$invdtto[0]; 
    $tablemain="";
    $tablemain .="<p class='printable'><table class='table6'><tr>                                            
                                        <th style='text-align: center;font-weight:bold;'>
                                        MEGA PROPERTIES GROUP SALES REPORT [KIOSKS AND TROLLYS] | PERIOD : $invoice_date_from TO $invoice_date_to
                                        </th></tr></table>";
    $tablemain .="</p>";
    $custom = array('result'=> $tablemain,'s'=>'Success');
    $response_array[] = $custom;    
    echo '{"error":'.json_encode($response_array).'}';
}
catch (Exception $err)
{
    $custom = array('result'=> "Error: ".$err->getMessage().", Line No:".$err->getLine(),'s'=>'Success');
    $response_array[] = $custom;
    echo '{"error":'.json_encode($response_array).'}';
}
?>